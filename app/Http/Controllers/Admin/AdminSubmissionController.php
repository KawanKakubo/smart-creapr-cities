<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\DiagnosticQuestion;
use App\Models\ProgramEvent;
use App\Models\User;
use App\Mail\ApprovalNotification;
use App\Mail\CredentialsEmail;
use App\Mail\ConfirmationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSubmissionController extends Controller
{
    /**
     * Exibe o dashboard com estatísticas
     */
    /**
     * Exibe o dashboard com estatísticas
     */
    public function dashboard()
    {
        // Estatísticas gerais
        $totalSubmissoes = Submission::active()->count();
        $pendentes = Submission::active()->where('status', 'pending')->count();
        $aprovadas = Submission::active()->where('status', 'approved')->count();
        $emAnalise = Submission::active()->where('status', 'under_review')->count();
        $rejeitadas = Submission::active()->where('status', 'rejected')->count();
        
        // Mais Engenharia
        $maisEngenharia = Submission::active()->where('faz_parte_mais_engenharia', true)->count();
        $naoMaisEngenharia = Submission::active()->where('faz_parte_mais_engenharia', false)->count();
        
        // Diagnósticos completos
        $diagnosticosCompletos = Submission::active()
            ->whereNotNull('diagnostico_estimulo_concluido_em')
            ->whereNotNull('diagnostico_educacao_concluido_em')
            ->whereNotNull('diagnostico_estruturas_concluido_em')
            ->count();
        
        // Médias de pontuação
        $mediaPontuacaoEstimulo = Submission::active()
            ->where('status', 'approved')
            ->whereNotNull('diagnostico_estimulo_concluido_em')
            ->avg('pontuacao_estimulo') ?? 0;
            
        $mediaPontuacaoEducacao = Submission::active()
            ->where('status', 'approved')
            ->whereNotNull('diagnostico_educacao_concluido_em')
            ->avg('pontuacao_educacao') ?? 0;
            
        $mediaPontuacaoEstruturas = Submission::active()
            ->where('status', 'approved')
            ->whereNotNull('diagnostico_estruturas_concluido_em')
            ->avg('pontuacao_estruturas') ?? 0;
        
        // Distribuição por regional
        $porRegional = Submission::active()
            ->select('regional_creapr', DB::raw('count(*) as total'))
            ->groupBy('regional_creapr')
            ->orderBy('total', 'desc')
            ->get();
        
        // Últimas submissões
        $ultimasSubmissoes = Submission::active()->with('user')->latest()->take(10)->get();
        
        // Timeline de submissões (últimos 6 meses)
        $timeline = Submission::active()
            ->select(
                DB::raw("TO_CHAR(created_at, 'YYYY-MM') as mes"),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();
        
        // Próximos eventos
        $upcomingEvents = ProgramEvent::upcoming()->take(5)->get();

        // Bloqueio de inscrições
        $registrationsBlocked = \App\Models\SystemSetting::get('registrations_blocked', false);
        
        return view('admin.dashboard', compact(
            'totalSubmissoes',
            'pendentes',
            'aprovadas',
            'emAnalise',
            'rejeitadas',
            'maisEngenharia',
            'naoMaisEngenharia',
            'diagnosticosCompletos',
            'mediaPontuacaoEstimulo',
            'mediaPontuacaoEducacao',
            'mediaPontuacaoEstruturas',
            'porRegional',
            'ultimasSubmissoes',
            'timeline',
            'upcomingEvents',
            'registrationsBlocked'
        ));
    }

    /**
     * Lista todas as submissões com filtros
     */
    public function index(Request $request)
    {
        $request->validate([
            'municipio' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,approved,under_review,rejected',
            'regional' => 'nullable|string|max:255',
            'mais_engenharia' => 'nullable|in:sim,nao',
            'diagnostico' => 'nullable|in:completo,parcial,nao_iniciado',
            'status_ativo' => 'nullable|in:ativos,inativos,todos',
        ]);
        
        $query = Submission::with('user');
        
        // Filtro de Ativo/Inativo (Padrão: apenas ativos)
        $statusAtivo = $request->input('status_ativo', 'ativos');
        if ($statusAtivo === 'ativos') {
            $query->active();
        } elseif ($statusAtivo === 'inativos') {
            $query->where('is_active', false);
        }
        
        // Filtros
        if ($request->filled('municipio')) {
            $query->where('municipio_nome', 'like', '%' . $request->municipio . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('regional')) {
            $query->where('regional_creapr', $request->regional);
        }
        
        if ($request->filled('mais_engenharia')) {
            $query->where('faz_parte_mais_engenharia', $request->mais_engenharia === 'sim');
        }

        if ($request->input('diagnostico') === 'completo') {
            $query->whereNotNull('diagnostico_estimulo_concluido_em')
                ->whereNotNull('diagnostico_educacao_concluido_em')
                ->whereNotNull('diagnostico_estruturas_concluido_em');
        } elseif ($request->input('diagnostico') === 'parcial') {
            $query->where(function ($statusQuery) {
                $statusQuery->whereNotNull('diagnostico_estimulo_iniciado_em')
                    ->orWhereNotNull('diagnostico_educacao_iniciado_em')
                    ->orWhereNotNull('diagnostico_estruturas_iniciado_em');
            })->where(function ($statusQuery) {
                $statusQuery->whereNull('diagnostico_estimulo_concluido_em')
                    ->orWhereNull('diagnostico_educacao_concluido_em')
                    ->orWhereNull('diagnostico_estruturas_concluido_em');
            });
        } elseif ($request->input('diagnostico') === 'nao_iniciado') {
            $query->whereNull('diagnostico_estimulo_iniciado_em')
                ->whereNull('diagnostico_educacao_iniciado_em')
                ->whereNull('diagnostico_estruturas_iniciado_em');
        }
        
        $submissions = $query->latest()->paginate(20)->withQueryString();
        
        // Lista de regionais para filtro
        $regionais = Submission::distinct()->pluck('regional_creapr')->filter();
        
        return view('admin.submissions.index', compact('submissions', 'regionais'));
    }
    
    /**
     * Exibe detalhes de uma submissão específica
     */
    public function show(Submission $submission)
    {
        $submission->load(['user', 'committeeMembers', 'diagnosticAnswers.question']);
        
        return view('admin.submissions.show', compact('submission'));
    }
    
    /**
     * Alterna o estado ativo/inativo de um município
     */
    public function toggleActive(Submission $submission)
    {
        $submission->is_active = !$submission->is_active;
        $submission->save();
        
        if (!$submission->is_active) {
            Log::info("Município {$submission->municipio_nome} (ID: {$submission->id}) foi INATIVADO.");
            $msg = 'Município INATIVADO com sucesso! Ele foi removido das relações, gráficos e não poderá acessar a plataforma.';
        } else {
            Log::info("Município {$submission->municipio_nome} (ID: {$submission->id}) foi REATIVADO.");
            $msg = 'Município REATIVADO com sucesso!';
        }
        
        return redirect()->back()->with('success', $msg);
    }
    
    /**
     * Atualiza o status de uma submissão (aprovar/rejeitar)
     */
    public function updateStatus(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,under_review,rejected',
            'observacao' => 'nullable|string|max:1000',
        ]);
        
        $oldStatus = $submission->status;
        $submission->status = $validated['status'];
        $submission->status_observacao = $validated['observacao'] ?? null;
        $submission->save();
        
        // Se foi aprovado e tem usuário, envia email de notificação
        if ($validated['status'] === 'approved' && $oldStatus !== 'approved' && $submission->user) {
            try {
                Mail::to($submission->user->email)->send(new ApprovalNotification($submission));
                Log::info('Email de aprovação enviado', [
                    'submission_id' => $submission->id,
                    'email' => $submission->user->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de aprovação', [
                    'submission_id' => $submission->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return redirect()->back()->with('success', 'Status atualizado com sucesso!');
    }
    
    /**
     * Exporta submissões para CSV
     */
    public function export(Request $request)
    {
        $query = Submission::with('user');
        
        // Filtro de Ativo/Inativo (Padrão: apenas ativos)
        $statusAtivo = $request->input('status_ativo', 'ativos');
        if ($statusAtivo === 'ativos') {
            $query->active();
        } elseif ($statusAtivo === 'inativos') {
            $query->where('is_active', false);
        }
        
        // Aplicar filtros
        if ($request->filled('municipio')) {
            $query->where('municipio_nome', 'like', '%' . $request->municipio . '%');
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('regional')) {
            $query->where('regional_creapr', $request->regional);
        }
        
        $submissions = $query->latest()->get();
        
        $filename = 'submissoes_smart_crea_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($submissions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
            
            fputcsv($file, [
                'Protocolo',
                'Status',
                'Ativo',
                'Município',
                'Habitantes',
                'Regional CREA-PR',
                'Setores Econômicos',
                'Mais Engenharia',
                'Responsável Nome',
                'Responsável Email',
                'Responsável Telefone',
                'Pontuação Estímulo',
                'Pontuação Educação',
                'Pontuação Estruturas',
                'Score Total',
                'Diagnóstico Completo',
                'Data Criação',
            ]);
            
            foreach ($submissions as $sub) {
                fputcsv($file, [
                    $sub->protocolo,
                    $sub->status,
                    $sub->is_active ? 'Sim' : 'Não',
                    $sub->municipio_nome,
                    $sub->habitantes_num,
                    $sub->regional_creapr,
                    is_array($sub->setores_economicos) ? implode(', ', $sub->setores_economicos) : '',
                    $sub->faz_parte_mais_engenharia ? 'Sim' : 'Não',
                    $sub->responsavel_nome ?? '',
                    $sub->responsavel_email ?? '',
                    $sub->responsavel_telefone ?? '',
                    $sub->pontuacao_estimulo ?? 0,
                    $sub->pontuacao_educacao ?? 0,
                    $sub->pontuacao_estruturas ?? 0,
                    $sub->getTotalScore(),
                    $sub->allDiagnosticsCompleted() ? 'Sim' : 'Não',
                    $sub->created_at->format('d/m/Y H:i'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Alterna o estado de bloqueio do sistema de inscrições.
     */
    public function toggleRegistration(Request $request)
    {
        $current = \App\Models\SystemSetting::get('registrations_blocked', false);
        \App\Models\SystemSetting::set('registrations_blocked', !$current);
        
        $message = !$current 
            ? 'Sistema de inscrições BLOQUEADO com sucesso! Novos municípios não poderão se cadastrar.' 
            : 'Sistema de inscrições DESBLOQUEADO com sucesso! Novos municípios podem se cadastrar normalmente.';
            
        return redirect()->back()->with('success', $message);
    }

    /**
     * Alterna a participação do município no programa Mais Engenharia.
     */
    public function updateMaisEngenharia(Request $request, Submission $submission)
    {
        $newState = !$submission->faz_parte_mais_engenharia;
        $submission->faz_parte_mais_engenharia = $newState;
        $submission->save();

        $msg = $newState 
            ? 'Município ADICIONADO ao programa Mais Engenharia com sucesso!' 
            : 'Município REMOVIDO do programa Mais Engenharia com sucesso!';

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Desbloqueia um eixo do diagnóstico para permitir reenvio.
     */
    public function unlockDiagnostic(Request $request, Submission $submission, $category)
    {
        $validCategories = ['estimulo', 'educacao', 'estruturas'];
        
        if (!in_array($category, $validCategories)) {
            return redirect()->back()->with('error', 'Categoria de diagnóstico inválida.');
        }

        // Limpa a data de conclusão
        $completedField = "diagnostico_{$category}_concluido_em";
        $submission->$completedField = null;
        
        // Zera temporariamente a pontuação
        $scoreField = "pontuacao_{$category}";
        $submission->$scoreField = 0;
        
        $submission->save();

        return redirect()->back()->with('success', "O eixo de " . ucfirst($category) . " foi desbloqueado com sucesso! O município agora pode continuar o preenchimento sem perder as respostas anteriores.");
    }
}
