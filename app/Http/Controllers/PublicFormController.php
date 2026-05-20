<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\CredentialsEmail;
use App\Mail\ConfirmationEmail;

class PublicFormController extends Controller
{
    /**
     * Exibe o formulário de manifestação de interesse
     */
    public function show()
    {
        if (\App\Models\SystemSetting::get('registrations_blocked', false)) {
            return view('public.blocked');
        }
        return view('public.manifestacao');
    }

    /**
     * Processa e salva a submissão do formulário
     */
    public function store(Request $request)
    {
        if (\App\Models\SystemSetting::get('registrations_blocked', false)) {
            return response()->json([
                'success' => false,
                'message' => 'O período para inscrições finalizou.'
            ], 403);
        }

        // Validação básica dos campos obrigatórios
        $validated = $request->validate([
            'municipio_nome' => 'required|string|max:255',
            'habitantes_num' => 'required|integer|min:1',
            'regional_creapr' => 'required|string|max:255',
            'setores_economicos' => 'required|array|min:1',
            'setores_economicos.*' => 'string|max:255',
            'secretaria_inovacao' => 'nullable|string|max:255',
            'secretaria_tecnologia_smart' => 'nullable|string|max:255',
            'secretaria_engenharia' => 'nullable|string|max:255',
            'faz_parte_mais_engenharia' => 'required|in:true,false',
            // Campos do responsável - sempre obrigatórios na nova UX
            'responsavel_nome' => 'required|string|max:255',
            'responsavel_cpf' => 'required|string|max:14',
            'responsavel_telefone' => 'required|string|max:20',
            'responsavel_email' => 'required|email|max:255',
            'responsavel_orgao' => 'required|string|max:255',
            'responsavel_funcao' => 'required|string|max:255',
            'orgao_endereco' => 'nullable|string|max:500',
            // Campos do prefeito - sempre obrigatórios na nova UX
            'prefeito_nome' => 'required|string|max:255',
            'prefeito_cpf' => 'required|string|max:14',
            'prefeito_telefone' => 'required|string|max:20',
            'prefeito_mandato' => 'required|string|max:255',
        ]);
        
        // Converte string 'true'/'false' para boolean
        $validated['faz_parte_mais_engenharia'] = $validated['faz_parte_mais_engenharia'] === 'true';
        
        // Adiciona valores padrão para campos obrigatórios não preenchidos na manifestação
        // Estes campos serão preenchidos posteriormente no diagnóstico completo
        $defaults = [
            // Políticas Públicas
            'possui_lei_inovacao' => false,
            'possui_politica_governo_digital' => false,
            'realizou_cpsi' => false,
            'possui_masterplan' => false,
            'possui_plano_diretor_smart' => false,
            'possui_politica_sandbox' => false,
            'possui_fundo_inovacao' => false,
            'possui_conselho_cti' => false,
            'possui_normativa_governanca' => false,
            'possui_secretaria_cti' => false,
            'rodou_contrato_solucao_inovadora' => false,
            'possui_politica_living_lab' => false,
            'possui_estrategia_transformacao_digital' => false,
            // Gestão e Tecnologia
            'conhece_comunidade_inteligente' => false,
            'usa_ferramenta_gestao_identidades' => false,
            'possui_normativa_lgpd' => false,
            'utiliza_sistemas_interoperaveis' => false,
            // Educação
            'escolas_fundamental_inicial_municipal' => 0,
            'escolas_fundamental_final_municipal' => 0,
            'escolas_fundamental_final_estadual' => 0,
            'escolas_ensino_medio_estadual' => 0,
            'realizou_hackathons_educacao' => false,
            'tem_universidade_presencial' => false,
            'aplica_novos_letramentos' => false,
            // Ecossistema
            'startups_num' => 0,
            'ambientes_inovacao' => 0,
            'hackathons_realizados' => 0,
            // Planejamento
            'possui_planejamento_estrategico' => false,
            'relevancia_engenharias' => false,
            'relevancia_engenharias_descricao' => '',
            // Prêmios
            'ganhou_premio_inovacao' => false,
            // Ponto Focal (usa dados do responsável da manifestação)
            'ponto_focal_nome' => $validated['responsavel_nome'] ?? '',
            'ponto_focal_cargo' => $validated['responsavel_funcao'] ?? '',
            'ponto_focal_email' => $validated['responsavel_email'] ?? '',
            'ponto_focal_telefone' => $validated['responsavel_telefone'] ?? '',
            'ponto_focal_celular' => $validated['responsavel_telefone'] ?? '',
            // Declaração
            'declaracao_interesse' => true,
        ];
        
        // Merge defaults com validated (validated tem prioridade)
        $validated = array_merge($defaults, $validated);
        
        // Gera o protocolo único
        $year = date('Y');
        $lastSubmission = Submission::whereYear('created_at', $year)->latest('id')->first();
        $sequence = $lastSubmission ? ((int) substr($lastSubmission->protocolo, -4)) + 1 : 1;
        $protocolo = sprintf('CREA-%s-%04d', $year, $sequence);
        
        // Gera token seguro e único para acesso
        $access_token = bin2hex(random_bytes(32)); // 64 caracteres hexadecimais
        $token_expires_at = now()->addDays(30); // Token válido por 30 dias
        
        // Adiciona o protocolo e token aos dados validados
        $validated['protocolo'] = $protocolo;
        $validated['access_token'] = $access_token;
        $validated['token_expires_at'] = $token_expires_at;
        $validated['status'] = 'pending';
        
        // Cria a submissão
        $submission = Submission::create($validated);
        
        // FLUXO DIFERENCIADO: Mais Engenharia Sim/Não
        if ($validated['faz_parte_mais_engenharia']) {
            // FLUXO COMPLETO: Cria usuário e dá acesso à plataforma
            
            try {
                $normalizedResponsibleEmail = Str::lower(trim($validated['responsavel_email']));

                // Verifica se o usuário já existe com esse email
                $user = User::where('email', $normalizedResponsibleEmail)->first();
                $isNewUser = false;
                
                if ($user) {
                    // Usuário já existe - verifica se precisa resetar senha
                    if ($user->is_temporary_password || $user->must_change_password) {
                        // Usuário tem senha temporária - gera nova senha mas SÓ salva após email ser enviado
                        $temporaryPassword = Str::random(12) . rand(10, 99);
                        $newPasswordHash = Hash::make($temporaryPassword);
                        
                        Log::info('Senha temporária gerada para usuário existente (aguardando envio de email)', [
                            'protocolo' => $protocolo,
                            'user_id' => $user->id,
                            'email' => $user->email,
                        ]);
                    } else {
                        // Usuário já definiu senha própria - não gera nova
                        $temporaryPassword = null;
                        
                        Log::info('Usuário existente vinculado (senha já definida)', [
                            'protocolo' => $protocolo,
                            'user_id' => $user->id,
                            'email' => $user->email,
                        ]);
                    }
                } else {
                    // Cria novo usuário
                    $isNewUser = true;
                    $temporaryPassword = Str::random(12) . rand(10, 99);
                    
                    $user = User::create([
                        'name' => $validated['responsavel_nome'],
                        'email' => $normalizedResponsibleEmail,
                        'password' => Hash::make($temporaryPassword),
                        'role' => 'municipality',
                        'is_temporary_password' => true,
                        'must_change_password' => true,
                    ]);
                    
                    Log::info('Novo usuário criado para Mais Engenharia', [
                        'protocolo' => $protocolo,
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);
                }
                
                // Vincula usuário à submission
                $submission->user_id = $user->id;
                $submission->save();
                
                // Envia email apropriado
                try {
                    if ($temporaryPassword) {
                        // Novo usuário OU senha resetada - envia credenciais
                        Mail::to($validated['responsavel_email'])->send(
                            new CredentialsEmail($user, $temporaryPassword, $protocolo, $validated['municipio_nome'])
                        );

                        // Só atualiza a senha no BD após o email ser enviado com sucesso
                        // (evita que falha de email bloqueie o usuário com uma senha desconhecida)
                        if (isset($newPasswordHash)) {
                            $user->password = $newPasswordHash;
                            $user->is_temporary_password = true;
                            $user->must_change_password = true;
                            $user->save();
                            Log::info('Senha temporária regenerada e email enviado com sucesso', [
                                'protocolo' => $protocolo,
                                'user_id' => $user->id,
                                'email' => $user->email,
                            ]);
                        } else {
                            Log::info('Email de credenciais enviado para novo usuário', [
                                'protocolo' => $protocolo,
                                'email' => $user->email,
                                'is_new_user' => $isNewUser,
                            ]);
                        }
                    } else {
                        // Usuário existente com senha própria - envia apenas confirmação
                        Mail::to($validated['responsavel_email'])->send(
                            new ConfirmationEmail($protocolo, $validated['municipio_nome'])
                        );
                        Log::info('Email de confirmação enviado para usuário com senha definida', [
                            'protocolo' => $protocolo,
                            'email' => $user->email,
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Erro ao enviar email', [
                        'protocolo' => $protocolo,
                        'error' => $e->getMessage(),
                    ]);
                }
                
                // Retorna JSON com sucesso
                return response()->json([
                    'success' => true,
                    'redirect' => route('inscricao.sucesso', [
                        'protocolo' => $protocolo,
                        'token' => $access_token
                    ]),
                    'message' => $temporaryPassword ? 'Usuário criado com sucesso!' : 'Manifestação registrada com sucesso!'
                ]);
                
            } catch (\Exception $e) {
                // Erro ao criar usuário - mas submission já foi criada
                Log::error('Erro no fluxo Mais Engenharia', [
                    'protocolo' => $protocolo,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Retorna sucesso mesmo assim (submission já existe)
                return response()->json([
                    'success' => true,
                    'redirect' => route('inscricao.sucesso', [
                        'protocolo' => $protocolo,
                        'token' => $access_token
                    ]),
                    'message' => 'Manifestação registrada. Entraremos em contato.'
                ]);
            }
            
        } else {
            // FLUXO SIMPLES: Apenas registra manifestação (sem criação de usuário)
            
            // Envia email de confirmação (sem credenciais)
            try {
                // Usa o email do responsável ou do município como fallback
                $emailDestino = $validated['responsavel_email'] ?? $validated['municipio_email'] ?? null;
                
                if ($emailDestino) {
                    Mail::to($emailDestino)->send(
                        new ConfirmationEmail($protocolo, $validated['municipio_nome'])
                    );
                    Log::info('Email de confirmação enviado', [
                        'protocolo' => $protocolo,
                        'email' => $emailDestino,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de confirmação', [
                    'protocolo' => $protocolo,
                    'error' => $e->getMessage(),
                ]);
            }
            
            Log::info('Manifestação registrada (não Mais Engenharia)', [
                'protocolo' => $protocolo,
                'municipio' => $validated['municipio_nome'],
            ]);
            
            // Retorna JSON com sucesso
            return response()->json([
                'success' => true,
                'redirect' => route('inscricao.sucesso', [
                    'protocolo' => $protocolo,
                    'token' => $access_token
                ])
            ]);
        }
    }

    /**
     * Exibe a página de confirmação com o protocolo
     */
    public function success($protocolo, $token)
    {
        // Busca a submission pelo protocolo
        $submission = Submission::where('protocolo', $protocolo)->firstOrFail();
        
        // Valida o access_token
        if ($submission->access_token !== $token) {
            abort(403, 'Token de acesso inválido.');
        }
        
        // Verifica se o token expirou
        if ($submission->token_expires_at && now()->gt($submission->token_expires_at)) {
            abort(403, 'Token de acesso expirado.');
        }
        
        // Determina o tipo de sucesso baseado no campo faz_parte_mais_engenharia
        $isComplete = $submission->faz_parte_mais_engenharia;
        $isSimple = !$submission->faz_parte_mais_engenharia;
        
        return view('public.success', compact('submission', 'isComplete', 'isSimple'));
    }
}
