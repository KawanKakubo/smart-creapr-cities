<?php

namespace Database\Seeders;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar ou atualizar usuário admin do sistema
        User::updateOrCreate(
            ['email' => 'kawanhrs@gmail.com'],
            [
                'name' => 'Kawan Harshe Kakubo',
                'password' => bcrypt('kawan1203'),
                'role' => 'admin',
                'is_temporary_password' => false,
                'must_change_password' => false,
            ]
        );

        // Login de teste para validar o fluxo de município em produção
        User::updateOrCreate(
            ['email' => 'municipio.teste@gmail.com'],
            [
                'name' => 'Município Teste',
                'password' => bcrypt('teste123@'),
                'role' => 'municipality',
                'is_temporary_password' => false,
                'must_change_password' => false,
            ]
        );

        $municipioUser = User::where('email', 'municipio.teste@gmail.com')->first();

        if ($municipioUser) {
            Submission::updateOrCreate(
                ['user_id' => $municipioUser->id],
                [
                    'protocolo' => 'TESTE-' . Str::upper(Str::random(10)),
                    'municipio_nome' => 'Município Teste',
                    'prefeito_nome' => 'Prefeito Teste',
                    'prefeito_mandato' => '1º Mandato',
                    'habitantes_num' => 10000,
                    'possui_lei_inovacao' => false,
                    'link_lei_inovacao' => null,
                    'possui_fundo_inovacao' => false,
                    'cnpj_fundo_inovacao' => null,
                    'possui_conselho_cti' => false,
                    'link_portaria_conselho' => null,
                    'possui_normativa_governanca' => false,
                    'link_normativa_governanca' => null,
                    'possui_secretaria_cti' => false,
                    'orgao_responsavel_cti' => null,
                    'rodou_contrato_solucao_inovadora' => false,
                    'link_evidencia_contrato' => null,
                    'possui_politica_sandbox' => false,
                    'link_evidencia_sandbox' => null,
                    'possui_politica_living_lab' => false,
                    'link_evidencia_living_lab' => null,
                    'possui_estrategia_transformacao_digital' => false,
                    'link_evidencia_estrategia' => null,
                    'startups_num' => 0,
                    'ambientes_inovacao' => [],
                    'hackathons_realizados' => [],
                    'possui_planejamento_estrategico' => false,
                    'link_evidencia_planejamento' => null,
                    'relevancia_engenharias' => 'media',
                    'relevancia_engenharias_descricao' => 'Cadastro de teste para validar o fluxo do município.',
                    'ganhou_premio_inovacao' => false,
                    'descricao_premio_relevante' => null,
                    'ponto_focal_nome' => 'Responsável Teste',
                    'ponto_focal_cargo' => 'Coordenador',
                    'ponto_focal_email' => 'municipio.teste@gmail.com',
                    'ponto_focal_telefone' => '(00) 0000-0000',
                    'ponto_focal_celular' => '(00) 00000-0000',
                    'declaracao_interesse' => true,
                    'status' => 'pending',
                    'status_observacao' => null,
                    'aprovado_em' => null,
                    'setores_economicos' => [],
                    'regional_creapr' => null,
                    'faz_parte_mais_engenharia' => false,
                    'pontuacao_estimulo' => 0,
                    'pontuacao_educacao' => 0,
                    'pontuacao_estruturas' => 0,
                    'diagnostico_estimulo_iniciado_em' => null,
                    'diagnostico_estimulo_concluido_em' => null,
                    'diagnostico_educacao_iniciado_em' => null,
                    'diagnostico_educacao_concluido_em' => null,
                    'diagnostico_estruturas_iniciado_em' => null,
                    'diagnostico_estruturas_concluido_em' => null,
                ]
            );
        }
    }
}
