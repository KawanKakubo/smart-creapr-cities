<?php

/**
 * Script para gerar relatório de municípios que concluíram o diagnóstico.
 * Detalha também os membros do comitê informados (um por linha) ou deixa 
 * em branco caso o município não tenha informado os membros.
 * 
 * Para executar, rode no terminal:
 * php gerar_relatorio_municipios.php
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Submission;

// Busca municípios que finalizaram os 3 eixos do diagnóstico
// e já carrega os membros do comitê para evitar consultas extras (Eager Loading)
$submissions = Submission::with('committeeMembers')
    ->whereNotNull('diagnostico_estimulo_concluido_em')
    ->whereNotNull('diagnostico_educacao_concluido_em')
    ->whereNotNull('diagnostico_estruturas_concluido_em')
    ->get();

$filename = 'relatorio_municipios_diagnostico_completo_' . date('Ymd_His') . '.csv';
$fp = fopen(__DIR__ . '/' . $filename, 'w');

// Adiciona UTF-8 BOM para abrir corretamente no Excel sem desconfigurar acentos
fputs($fp, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

// Cabeçalhos do CSV
fputcsv($fp, [
    'ID Submissao', 
    'Município', 
    'Protocolo',
    'Status',
    'Prefeito', 
    'Responsável', 
    'Email Responsável', 
    'Telefone Responsável',
    'Nome Ponto Focal',
    'Email Ponto Focal',
    'Informou Comitê?',
    'Membro Comitê - Nome',
    'Membro Comitê - Email',
    'Membro Comitê - Cargo',
    'Membro Comitê - Órgão'
], ';');

$count = 0;
foreach ($submissions as $sub) {
    $hasCommittee = $sub->committeeMembers->count() > 0 ? 'Sim' : 'Não';
    
    // Se o município informou membros do comitê, geramos uma linha para cada membro
    if ($sub->committeeMembers->count() > 0) {
        foreach ($sub->committeeMembers as $member) {
            fputcsv($fp, [
                $sub->id,
                $sub->municipio_nome,
                $sub->protocolo,
                $sub->status,
                $sub->prefeito_nome,
                $sub->responsavel_nome,
                $sub->responsavel_email,
                $sub->responsavel_telefone,
                $sub->ponto_focal_nome,
                $sub->ponto_focal_email,
                $hasCommittee,
                $member->nome,
                $member->email,
                $member->cargo,
                $member->orgao
            ], ';');
        }
    } else {
        // Se não tiver membro, imprime a linha apenas com os dados do município 
        // e as colunas de membro vazias
        fputcsv($fp, [
            $sub->id,
            $sub->municipio_nome,
            $sub->protocolo,
            $sub->status,
            $sub->prefeito_nome,
            $sub->responsavel_nome,
            $sub->responsavel_email,
            $sub->responsavel_telefone,
            $sub->ponto_focal_nome,
            $sub->ponto_focal_email,
            $hasCommittee,
            '', // Nome do membro
            '', // Email do membro
            '', // Cargo do membro
            ''  // Órgão do membro
        ], ';');
    }
    $count++;
}

fclose($fp);

echo "Relatorio gerado com sucesso!\n";
echo "Total de municipios que concluiram o diagnostico: {$count}\n";
echo "Arquivo salvo em: " . realpath(__DIR__ . '/' . $filename) . "\n";
