<?php

/**
 * Script para gerar relatório GERAL de municípios inscritos.
 * Traz todos os municípios (inclusive os que não fizeram o diagnóstico ou zeraram a pontuação).
 * Planilha detalhada com uma linha por município e colunas para os membros do comitê (até 5).
 * 
 * Para executar, rode no terminal:
 * php gerar_relatorio_geral_municipios.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Submission;

// Busca todas as submissões sem filtro de diagnóstico concluído
// e já carrega os membros do comitê (Eager Loading)
$submissions = Submission::with('committeeMembers')->get();

$filename = 'relatorio_geral_municipios_completo_' . date('Ymd_His') . '.csv';
$fp = fopen(__DIR__ . '/' . $filename, 'w');

// Adiciona UTF-8 BOM para abrir corretamente no Excel sem desconfigurar acentos
fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

// Cabeçalhos do CSV
$headers = [
    'ID Submissao',
    'Protocolo',
    'Data Cadastro',
    'Status Inscrição',
    'Município',
    'Regional CREA-PR',
    'Habitantes',
    'Prefeito Nome',
    'Prefeito Telefone',
    'Responsável Inscrição - Nome',
    'Responsável Inscrição - Email',
    'Responsável Inscrição - Telefone',
    'Responsável Inscrição - Órgão',
    'Responsável Inscrição - Função',
    'Ponto Focal - Nome',
    'Ponto Focal - Email',
    'Ponto Focal - Telefone/Celular',
    'Fez o Diagnóstico?', // Verifica se os 3 eixos foram preenchidos
    'Pontuação Estímulo',
    'Pontuação Educação',
    'Pontuação Estruturas',
    'Pontuação Total',
    'Faz parte Mais Engenharia?',
    'Possui Lei Inovação?',
    'Possui Fundo Inovação?',
    'Possui Conselho CTI?',
    'Possui Secretaria CTI?',
    'Possui Plano Diretor Smart?',
    'Possui Sandbox?',
    'Possui Comitê?', // Sim/Não
];

// Adiciona colunas para os 5 possíveis membros do comitê
for ($i = 1; $i <= 5; $i++) {
    $headers[] = "Membro $i - Nome";
    $headers[] = "Membro $i - Cargo";
    $headers[] = "Membro $i - Email";
    $headers[] = "Membro $i - Telefone";
    $headers[] = "Membro $i - Órgão";
}

fputcsv($fp, $headers, ';');

$count = 0;
foreach ($submissions as $sub) {
    // Verifica se concluiu o diagnóstico
    $fezDiagnostico = $sub->allDiagnosticsCompleted() ? 'Sim' : 'Não';
    
    // Status formatado
    $statusMap = [
        'approved' => 'Aprovado',
        'pending' => 'Pendente',
        'under_review' => 'Em Análise',
        'rejected' => 'Rejeitado',
    ];
    $statusDesc = $statusMap[$sub->status] ?? $sub->status;

    // Concatena telefones do ponto focal para exibir tudo em uma coluna
    $telefoneFocal = trim($sub->ponto_focal_telefone . ' ' . $sub->ponto_focal_celular);

    $row = [
        $sub->id,
        $sub->protocolo,
        $sub->created_at ? $sub->created_at->format('d/m/Y H:i') : '',
        $statusDesc,
        $sub->municipio_nome,
        $sub->regional_creapr,
        $sub->habitantes_num,
        $sub->prefeito_nome,
        $sub->prefeito_telefone,
        $sub->responsavel_nome,
        $sub->responsavel_email,
        $sub->responsavel_telefone,
        $sub->responsavel_orgao,
        $sub->responsavel_funcao,
        $sub->ponto_focal_nome,
        $sub->ponto_focal_email,
        $telefoneFocal,
        $fezDiagnostico,
        $sub->pontuacao_estimulo ?? 0,
        $sub->pontuacao_educacao ?? 0,
        $sub->pontuacao_estruturas ?? 0,
        $sub->getTotalScore(),
        $sub->faz_parte_mais_engenharia ? 'Sim' : 'Não',
        $sub->possui_lei_inovacao ? 'Sim' : 'Não',
        $sub->possui_fundo_inovacao ? 'Sim' : 'Não',
        $sub->possui_conselho_cti ? 'Sim' : 'Não',
        $sub->possui_secretaria_cti ? 'Sim' : 'Não',
        $sub->possui_plano_diretor_smart ? 'Sim' : 'Não',
        $sub->possui_politica_sandbox ? 'Sim' : 'Não',
        $sub->committeeMembers->count() > 0 ? 'Sim' : 'Não',
    ];

    // Adiciona os dados dos membros (até 5)
    $members = $sub->committeeMembers->values();
    for ($i = 0; $i < 5; $i++) {
        if (isset($members[$i])) {
            $member = $members[$i];
            $row[] = $member->nome;
            $row[] = $member->cargo;
            $row[] = $member->email;
            $row[] = $member->telefone;
            $row[] = $member->orgao;
        } else {
            // Se não tiver o membro, preenche com vazio para manter a estrutura do CSV
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
        }
    }

    fputcsv($fp, $row, ';');
    $count++;
}

fclose($fp);

echo "Relatorio GERAL gerado com sucesso!\n";
echo "Total de municipios processados: {$count}\n";
echo "Arquivo salvo em: " . realpath(__DIR__ . '/' . $filename) . "\n";
