<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkshopResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkshopTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_workshop_form_is_available_without_authentication(): void
    {
        $this->get(route('workshop.show'))
            ->assertOk()
            ->assertSee('Da ideia ao plano da cidade')
            ->assertSee('Cone de Voros')
            ->assertSee('Governança policêntrica');
    }

    public function test_morning_response_is_validated_saved_and_acknowledged(): void
    {
        $response = $this->post(route('workshop.store'), [
            'municipality' => 'Curitiba',
            'population_band' => 'Acima de 150 mil',
            'session' => 'manha',
            'answers' => [
                'axis' => 'Governo digital, dados e desburocratização',
                'challenge' => 'Reduzir o tempo de aprovação de projetos urbanos.',
                'gov_data' => 'Cruzar dados de obras e meio ambiente.',
                'gov_responsive' => 'Criar protocolo digital integrado.',
                'gov_corporate' => 'Comitê / decreto',
                'gov_collaborative' => 'Universidade e setor produtivo.',
                'gov_multilevel' => 'Consórcio regional.',
                'gov_polycentric' => 'Câmara técnica permanente.',
                'maturity' => ['Dados', 'Colaborativa'],
                'target_3e' => '[Eficiência] Reduzir o prazo em 30%.',
                'data_indicator' => 'Prazo médio atual de licenciamento.',
                'partners' => ['Universidades e ICTs', 'Setor produtivo / associações / CREA-PR'],
                'governance' => 'Comitê gestor / conselho',
                'action_72h' => 'Convocar a primeira reunião.',
                'action_30d' => 'Instalar a mesa intersetorial.',
            ],
        ]);

        $response->assertRedirect(route('workshop.show'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('workshop_responses', [
            'municipality' => 'Curitiba',
            'population_band' => 'Acima de 150 mil',
            'session' => 'manha',
        ]);

        $saved = WorkshopResponse::firstOrFail();
        $this->assertSame(['Dados', 'Colaborativa'], $saved->answers['maturity']);
        $this->assertSame('[Eficiência] Reduzir o prazo em 30%.', $saved->answers['target_3e']);
    }

    public function test_afternoon_response_saves_future_scenarios_and_backcasting(): void
    {
        $this->post(route('workshop.store'), [
            'municipality' => 'Londrina',
            'population_band' => '50 a 150 mil',
            'session' => 'tarde',
            'answers' => [
                'challenge' => 'Preparar a infraestrutura urbana para eventos climáticos extremos.',
                'trend_one' => 'Eventos climáticos extremos',
                'trend_two' => 'Transição energética e mobilidade elétrica',
                'weak_signal' => 'Aumento de alagamentos em chuvas curtas.',
                'projected_future' => 'Mais danos e interrupções de serviço.',
                'disruption_future' => 'Uma sequência de tempestades severas.',
                'preferred_future' => 'Infraestrutura resiliente e alerta antecipado.',
                'milestone_2030' => 'Rede de drenagem prioritária concluída.',
                'milestone_2027' => 'Mapa de risco e parceiros pactuados.',
                'pilot_90d' => 'Testar alerta comunitário em um bairro.',
                'continuity' => 'Formalizar a rede por decreto e conselho.',
            ],
        ])->assertRedirect(route('workshop.show'));

        $saved = WorkshopResponse::firstOrFail();
        $this->assertSame('tarde', $saved->session);
        $this->assertSame('Infraestrutura resiliente e alerta antecipado.', $saved->answers['preferred_future']);
        $this->assertSame('Testar alerta comunitário em um bairro.', $saved->answers['pilot_90d']);
    }

    public function test_invalid_municipality_session_and_missing_challenge_are_rejected(): void
    {
        $this->from(route('workshop.show'))
            ->post(route('workshop.store'), [
                'municipality' => str_repeat('X', 121),
                'population_band' => 'Porte inválido',
                'session' => 'noite',
                'answers' => ['challenge' => ''],
            ])
            ->assertRedirect(route('workshop.show'))
            ->assertSessionHasErrors(['municipality', 'population_band', 'session', 'answers.challenge']);

        $this->assertDatabaseCount('workshop_responses', 0);
    }

    public function test_unapproved_partner_and_governance_values_are_rejected(): void
    {
        $this->from(route('workshop.show'))
            ->post(route('workshop.store'), [
                'municipality' => 'Maringá',
                'population_band' => '20 a 50 mil',
                'session' => 'manha',
                'answers' => [
                    'challenge' => 'Organizar os dados municipais.',
                    'partners' => ['Parceiro inventado'],
                    'maturity' => ['Governança inventada'],
                ],
            ])
            ->assertSessionHasErrors(['answers.partners.0', 'answers.maturity.0']);

        $this->assertDatabaseCount('workshop_responses', 0);
    }

    public function test_admin_panel_requires_admin_role(): void
    {
        $this->get(route('admin.workshop.index'))
            ->assertRedirect(route('admin.login'));

        $municipalityUser = User::create([
            'name' => 'Usuário municipal',
            'email' => 'municipio@example.test',
            'password' => 'password',
            'role' => 'municipality',
        ]);

        $this->actingAs($municipalityUser)
            ->get(route('admin.workshop.index'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_panel_shows_counts_governance_maturity_and_session_filter(): void
    {
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        WorkshopResponse::create([
            'municipality' => 'Ponta Grossa',
            'population_band' => '50 a 150 mil',
            'session' => 'manha',
            'answers' => ['challenge' => 'Desafio da manhã', 'gov_data' => 'Cruzar dados municipais', 'maturity' => ['Dados'], 'partners' => ['Universidades e ICTs']],
        ]);
        WorkshopResponse::create([
            'municipality' => 'Ponta Grossa',
            'population_band' => '50 a 150 mil',
            'session' => 'tarde',
            'answers' => ['challenge' => 'Desafio da tarde', 'preferred_future' => 'Visão 2036'],
        ]);

        $this->actingAs($admin)
            ->get(route('admin.workshop.index'))
            ->assertOk()
            ->assertSee('Ponta Grossa')
            ->assertSee('Cidades participantes')
            ->assertSee('As seis governanças')
            ->assertSee('Desafio da manhã')
            ->assertSee('Desafio da tarde')
            ->assertSee('Universidades e ICTs')
            ->assertSee('Governança de dados')
            ->assertDontSee('Gov Data')
            ->assertDontSee('Megatendência 1')
            ->assertSee('fetch(window.location.href', false)
            ->assertSee('window.setInterval(refreshPanel, 8000)', false);

        $this->actingAs($admin)
            ->get(route('admin.workshop.index', ['session' => 'tarde']))
            ->assertOk()
            ->assertSee('Desafio da tarde')
            ->assertDontSee('Desafio da manhã');
    }

    public function test_admin_csv_export_includes_utf8_bom_headers_and_filtered_rows(): void
    {
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'export@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        WorkshopResponse::create([
            'municipality' => 'São José dos Pinhais',
            'population_band' => '50 a 150 mil',
            'session' => 'tarde',
            'answers' => ['challenge' => 'Plano climático', 'pilot_90d' => 'Piloto de alerta'],
        ]);
        WorkshopResponse::create([
            'municipality' => 'Cascavel',
            'population_band' => '50 a 150 mil',
            'session' => 'manha',
            'answers' => ['challenge' => 'Outra resposta'],
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.workshop.export', ['session' => 'tarde']))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8')
            ->assertHeader('content-disposition', 'attachment; filename=respostas-oficina-smart-crea-cities.csv');

        $csv = $response->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringContainsString('Município;Porte;Eixo;Desafio', $csv);
        $this->assertStringContainsString('São José dos Pinhais', $csv);
        $this->assertStringContainsString('Piloto de alerta', $csv);
        $this->assertStringNotContainsString('Cascavel', $csv);
    }
}
