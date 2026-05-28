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
        $originalState = $submission->faz_parte_mais_engenharia;
        $newState = !$originalState; // Toggle state

        $submission->faz_parte_mais_engenharia = $newState;

        if ($newState) {
            // Se está ativando Mais Engenharia:
            // Cria o usuário se não existir, envia e-mail com credenciais
            try {
                $normalizedResponsibleEmail = Str::lower(trim($submission->responsavel_email));

                if (empty($submission->responsavel_email) || empty($submission->responsavel_nome)) {
                    return redirect()->back()->with('error', 'Não é possível ativar o Mais Engenharia porque os dados de responsável da manifestação estão incompletos.');
                }

                // Verifica se o usuário já existe com esse email
                $user = User::where('email', $normalizedResponsibleEmail)->first();
                $temporaryPassword = null;
                $isNewUser = false;

                if ($user) {
                    // Usuário já existe - se tem senha temporária, gera uma nova
                    if ($user->is_temporary_password || $user->must_change_password) {
                        $temporaryPassword = Str::random(12) . rand(10, 99);
                        $newPasswordHash = Hash::make($temporaryPassword);
                    }
                } else {
                    // Cria novo usuário
                    $isNewUser = true;
                    $temporaryPassword = Str::random(12) . rand(10, 99);
                    
                    $user = User::create([
                        'name' => $submission->responsavel_nome,
                        'email' => $normalizedResponsibleEmail,
                        'password' => Hash::make($temporaryPassword),
                        'role' => 'municipality',
                        'is_temporary_password' => true,
                        'must_change_password' => true,
                    ]);
                }

                // Vincula usuário à submissão
                $submission->user_id = $user->id;
                $submission->save();

                // Envia e-mail
                if ($temporaryPassword) {
                    Mail::to($submission->responsavel_email)->send(
                        new CredentialsEmail($user, $temporaryPassword, $submission->protocolo, $submission->municipio_nome)
                    );

                    if (isset($newPasswordHash)) {
                        $user->password = $newPasswordHash;
                        $user->is_temporary_password = true;
                        $user->must_change_password = true;
                        $user->save();
                    }
                } else {
                    Mail::to($submission->responsavel_email)->send(
                        new ConfirmationEmail($submission->protocolo, $submission->municipio_nome)
                    );
                }

            } catch (\Exception $e) {
                Log::error('Erro ao ativar Mais Engenharia pelo Admin', [
                    'submission_id' => $submission->id,
                    'error' => $e->getMessage()
                ]);
                // Reverte estado
                $submission->faz_parte_mais_engenharia = false;
                $submission->user_id = null;
                $submission->save();
                
                return redirect()->back()->with('error', 'Erro ao processar ativação: ' . $e->getMessage());
            }
        } else {
            // Se está desativando Mais Engenharia:
            // Remove o vínculo do usuário da submissão.
            $associatedUser = $submission->user;
            if ($associatedUser) {
                $submission->user_id = null;
                $submission->save();

                // Deleta o usuário se ele não tiver outras submissões associadas
                $otherSubmissions = Submission::where('user_id', $associatedUser->id)->count();
                if ($otherSubmissions === 0) {
                    $associatedUser->delete();
                }
            }
            $submission->save();
        }

        $msg = $newState 
            ? 'Município ADICIONADO ao programa Mais Engenharia com sucesso! As credenciais de acesso foram geradas/enviadas.' 
            : 'Município REMOVIDO do programa Mais Engenharia com sucesso! A conta de acesso associada foi revogada.';

        return redirect()->back()->with('success', $msg);
    }
}
