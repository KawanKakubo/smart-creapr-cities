<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\User;
use App\Mail\CustomMunicipalityEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminEmailController extends Controller
{
    /**
     * Exibe o formulário de composição de e-mail
     */
    public function create()
    {
        // Pega apenas municípios ativos
        $municipios = Submission::active()
            ->orderBy('municipio_nome')
            ->get();

        return view('admin.emails.create', compact('municipios'));
    }

    /**
     * Envia o e-mail customizado para os destinatários selecionados
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'recipient_type' => 'required|in:all,approved,pending,selected,custom',
            'selected_municipios' => 'required_if:recipient_type,selected|array',
            'selected_municipios.*' => 'exists:submissions,id',
            'custom_email' => 'required_if:recipient_type,custom|string',
        ]);

        $sentCount = 0;
        $hasPasswordTag = Str::contains($validated['body'], '{senha_municipio}') || Str::contains($validated['subject'], '{senha_municipio}');

        if ($validated['recipient_type'] === 'custom') {
            // Processa destinatários informados manualmente (separados por vírgula)
            $emails = array_map('trim', explode(',', $validated['custom_email']));
            $emails = array_filter($emails, function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });

            if (empty($emails)) {
                return redirect()->back()->with('error', 'Por favor, informe ao menos um endereço de e-mail válido.');
            }

            foreach ($emails as $email) {
                try {
                    $temporaryPassword = null;
                    
                    // Procura se esse e-mail já pertence a alguma submissão ativa para preencher os dados reais do município
                    $submission = Submission::active()
                        ->where('responsavel_email', 'ilike', $email)
                        ->first();

                    if ($submission) {
                        if ($hasPasswordTag) {
                            $temporaryPassword = Str::random(12) . rand(10, 99);
                            $normalizedEmail = Str::lower(trim($submission->responsavel_email));
                            $user = $submission->user;

                            if (!$user) {
                                $user = User::create([
                                    'name' => $submission->responsavel_nome,
                                    'email' => $normalizedEmail,
                                    'password' => Hash::make($temporaryPassword),
                                    'role' => 'municipality',
                                    'is_temporary_password' => true,
                                    'must_change_password' => true,
                                ]);
                                $submission->user_id = $user->id;
                                $submission->faz_parte_mais_engenharia = true;
                                $submission->save();
                            } else {
                                $user->password = Hash::make($temporaryPassword);
                                $user->is_temporary_password = true;
                                $user->must_change_password = true;
                                $user->save();
                            }
                        }

                        $tags = [
                            '{municipio_nome}' => $submission->municipio_nome,
                            '{responsavel_nome}' => $submission->responsavel_nome ?? 'Responsável',
                            '{responsavel_email}' => $submission->responsavel_email ?? '',
                            '{protocolo}' => $submission->protocolo,
                            '{senha_municipio}' => $temporaryPassword ?? '***',
                        ];
                    } else {
                        // Fallback caso seja um e-mail arbitrário e não associado a um município
                        $tags = [
                            '{municipio_nome}' => 'Município Cadastrado',
                            '{responsavel_nome}' => 'Responsável',
                            '{responsavel_email}' => $email,
                            '{protocolo}' => 'PR-XXXX',
                            '{senha_municipio}' => 'N/A',
                        ];
                    }

                    $resolvedSubject = str_replace(array_keys($tags), array_values($tags), $validated['subject']);
                    $resolvedBody = str_replace(array_keys($tags), array_values($tags), $validated['body']);

                    Mail::to($email)->send(new CustomMunicipalityEmail($resolvedSubject, $resolvedBody));
                    $sentCount++;

                } catch (\Exception $e) {
                    Log::error("Erro ao enviar e-mail customizado para {$email}: " . $e->getMessage());
                }
            }

        } else {
            // Processa municípios da base
            $query = Submission::active();

            if ($validated['recipient_type'] === 'approved') {
                $query->where('status', 'approved');
            } elseif ($validated['recipient_type'] === 'pending') {
                $query->where('status', 'pending');
            } elseif ($validated['recipient_type'] === 'selected') {
                $query->whereIn('id', $validated['selected_municipios']);
            }

            $recipients = $query->get();

            if ($recipients->isEmpty()) {
                return redirect()->back()->with('error', 'Nenhum município ativo atende aos critérios selecionados.');
            }

            foreach ($recipients as $submission) {
                try {
                    $temporaryPassword = null;
                    
                    if ($hasPasswordTag) {
                        $temporaryPassword = Str::random(12) . rand(10, 99);
                        $normalizedEmail = Str::lower(trim($submission->responsavel_email));

                        if (empty($normalizedEmail) || empty($submission->responsavel_nome)) {
                            Log::warning("Não foi possível gerar credenciais para a submissão {$submission->id} porque faltam dados de contato.");
                            continue;
                        }

                        $user = $submission->user;

                        if (!$user) {
                            $user = User::create([
                                'name' => $submission->responsavel_nome,
                                'email' => $normalizedEmail,
                                'password' => Hash::make($temporaryPassword),
                                'role' => 'municipality',
                                'is_temporary_password' => true,
                                'must_change_password' => true,
                            ]);

                            $submission->user_id = $user->id;
                            $submission->faz_parte_mais_engenharia = true;
                            $submission->save();
                        } else {
                            $user->password = Hash::make($temporaryPassword);
                            $user->is_temporary_password = true;
                            $user->must_change_password = true;
                            $user->save();
                        }
                    }

                    $tags = [
                        '{municipio_nome}' => $submission->municipio_nome,
                        '{responsavel_nome}' => $submission->responsavel_nome ?? 'Responsável',
                        '{responsavel_email}' => $submission->responsavel_email ?? '',
                        '{protocolo}' => $submission->protocolo,
                        '{senha_municipio}' => $temporaryPassword ?? '***',
                    ];

                    $resolvedSubject = str_replace(array_keys($tags), array_values($tags), $validated['subject']);
                    $resolvedBody = str_replace(array_keys($tags), array_values($tags), $validated['body']);

                    Mail::to($submission->responsavel_email)->send(new CustomMunicipalityEmail($resolvedSubject, $resolvedBody));
                    $sentCount++;

                } catch (\Exception $e) {
                    Log::error("Erro ao enviar e-mail customizado para a submissão ID {$submission->id}: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.emails.create')->with('success', "Campanha finalizada! {$sentCount} e-mail(s) enviado(s) com sucesso.");
    }
}
