<?php

namespace Tests\Feature;

use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TrilhaFormativaTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_page_can_be_rendered(): void
    {
        $response = $this->get('/manifestacao-interesse');

        $response->assertStatus(200);
    }

    public function test_municipality_can_submit_form_successfully(): void
    {
        Mail::fake();

        $response = $this->post('/manifestacao-interesse', [
            'municipio_nome' => 'Cidade Teste',
            'habitantes_num' => 50000,
            'regional_creapr' => 'Curitiba',
            'setores_economicos' => ['Agricultura'],
            'faz_parte_mais_engenharia' => 'true',
            'responsavel_nome' => 'Responsável Teste',
            'responsavel_cpf' => '123.456.789-00',
            'responsavel_telefone' => '(41) 99999-9999',
            'responsavel_email' => 'responsavel@teste.com',
            'responsavel_orgao' => 'Prefeitura',
            'responsavel_funcao' => 'Secretário',
            'prefeito_nome' => 'Prefeito Teste',
            'prefeito_cpf' => '987.654.321-00',
            'prefeito_telefone' => '(41) 88888-8888',
            'prefeito_mandato' => '2021-2024',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('submissions', [
            'municipio_nome' => 'Cidade Teste',
            'responsavel_email' => 'responsavel@teste.com',
            'faz_parte_mais_engenharia' => true,
        ]);
        
        // Verifica se o usuário do município foi criado
        $this->assertDatabaseHas('users', [
            'email' => 'responsavel@teste.com',
            'role' => 'municipality',
        ]);
    }

    public function test_form_validation_prevents_huge_inhabitants_number(): void
    {
        $response = $this->post('/manifestacao-interesse', [
            'municipio_nome' => 'Cidade Teste',
            'habitantes_num' => 12159311592, // Número que estava quebrando o banco (12 bi)
            'regional_creapr' => 'Curitiba',
            'setores_economicos' => ['Agricultura'],
            'faz_parte_mais_engenharia' => 'true',
            'responsavel_nome' => 'Responsável Teste',
            'responsavel_cpf' => '123.456.789-00',
            'responsavel_telefone' => '(41) 99999-9999',
            'responsavel_email' => 'responsavel@teste.com',
            'responsavel_orgao' => 'Prefeitura',
            'responsavel_funcao' => 'Secretário',
            'prefeito_nome' => 'Prefeito Teste',
            'prefeito_cpf' => '987.654.321-00',
            'prefeito_telefone' => '(41) 88888-8888',
            'prefeito_mandato' => '2021-2024',
        ]);

        // A submissão deve falhar e retornar erro de validação
        $response->assertStatus(302);
        $response->assertSessionHasErrors('habitantes_num');
    }
}
