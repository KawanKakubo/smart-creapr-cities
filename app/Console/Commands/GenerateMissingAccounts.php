<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Submission;
use App\Models\User;
use App\Mail\CredentialsEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class GenerateMissingAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:gerar-acessos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera contas de acesso e envia credenciais para municípios que ainda não possuem usuário associado.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando varredura por municípios sem acesso ao dashboard...');
        
        $submissions = Submission::active()->whereNull('user_id')->get();
        
        if ($submissions->isEmpty()) {
            $this->info('Nenhum município pendente de acesso encontrado.');
            return;
        }

        $this->info('Encontrados ' . $submissions->count() . ' municípios sem usuário. Gerando acessos...');

        $count = 0;
        foreach ($submissions as $submission) {
            $email = Str::lower(trim($submission->responsavel_email));
            
            if (empty($email)) {
                $this->error('Município ' . $submission->municipio_nome . ' não possui e-mail do responsável válido. Pulando...');
                continue;
            }

            try {
                // Verifica se usuário já existe com o email
                $user = User::where('email', $email)->first();
                $temporaryPassword = Str::random(12) . rand(10, 99);
                $newPasswordHash = Hash::make($temporaryPassword);
                
                if ($user) {
                    $this->line("Usuário já existe para o e-mail $email. Resetando senha e vinculando...");
                    // Vincula
                    $submission->user_id = $user->id;
                    $submission->save();
                } else {
                    $this->line("Criando novo usuário para $email...");
                    $user = User::create([
                        'name' => $submission->responsavel_nome,
                        'email' => $email,
                        'password' => $newPasswordHash, // Senha aleatória
                        'role' => 'municipality',
                        'is_temporary_password' => true,
                        'must_change_password' => true,
                    ]);
                    // Vincula
                    $submission->user_id = $user->id;
                    $submission->save();
                }
                
                // Envia credencial por e-mail (vai pra fila)
                Mail::to($email)->send(
                    new CredentialsEmail($user, $temporaryPassword, $submission->protocolo, $submission->municipio_nome)
                );
                
                // Atualiza a senha no banco APENAS após o e-mail ser enviado/enfileirado com sucesso
                $user->password = $newPasswordHash;
                $user->is_temporary_password = true;
                $user->must_change_password = true;
                $user->save();
                
                $this->info("Sucesso: Acesso gerado e enviado para {$submission->municipio_nome} ($email)");
                $count++;
                
            } catch (\Exception $e) {
                $this->error("Erro ao gerar acesso para {$submission->municipio_nome}: " . $e->getMessage());
                Log::error('Erro ao gerar acesso no comando app:gerar-acessos', [
                    'submission_id' => $submission->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $this->info("Processo concluído! $count contas geradas e e-mails de credenciais enviados para a fila.");
    }
}
