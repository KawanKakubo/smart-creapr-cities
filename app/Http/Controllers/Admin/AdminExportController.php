<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\DiagnosticQuestion;
use App\Models\DiagnosticAnswer;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminExportController extends Controller
{
    /**
     * Auxiliar para traduzir categorias
     */
    private function getCategoryName($key)
    {
        $map = [
            'estimulo' => 'Estímulo',
            'educacao' => 'Educação',
            'estruturas' => 'Estruturas',
        ];
        return $map[$key] ?? ucfirst($key);
    }

    /**
     * Auxiliar para formatar respostas
     */
    private function getFormattedAnswer($answer)
    {
        if (!$answer) {
            return 'Não respondido';
        }
        
        $qType = $answer->question->type ?? 'text';
        
        switch ($qType) {
            case 'yes_no':
            case 'yes_no_evidence':
                $val = $answer->answer_yes_no === 'yes' ? 'Sim' : ($answer->answer_yes_no === 'no' ? 'Não' : 'Não respondido');
                if ($answer->evidence_url) {
                    $val .= " (Evidência: " . $answer->evidence_url . ")";
                }
                return $val;
                
            case 'checkbox':
                if (is_array($answer->answer_checkboxes)) {
                    return implode(', ', $answer->answer_checkboxes);
                }
                return $answer->answer_checkboxes ?? 'Não respondido';
                
            case 'multiple_input':
                if (is_array($answer->answer_multiple_input)) {
                    $parts = [];
                    foreach ($answer->answer_multiple_input as $key => $value) {
                        if (!empty($value)) {
                            $parts[] = "{$key}: {$value}";
                        }
                    }
                    return implode(' | ', $parts);
                }
                return 'Não respondido';
                
            case 'repeatable_fields':
                if (is_array($answer->answer_multiple_input)) {
                    $entries = [];
                    foreach ($answer->answer_multiple_input as $idx => $entry) {
                        if (is_array($entry)) {
                            $fields = [];
                            foreach ($entry as $k => $v) {
                                if (!empty($v)) {
                                    $fields[] = "{$k}: {$v}";
                                }
                            }
                            if (!empty($fields)) {
                                $entries[] = "Item " . ($idx + 1) . " (" . implode(', ', $fields) . ")";
                            }
                        }
                    }
                    return implode('; ', $entries);
                }
                return 'Não respondido';
                
            case 'text':
                return $answer->answer_text ?? 'Não respondido';
                
            default:
                return 'Não respondido';
        }
    }

    /**
     * Exporta as respostas de um único município para Excel (.xlsx)
     */
    public function exportAnswersXlsx(Submission $submission)
    {
        $questions = DiagnosticQuestion::where('is_active', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Respostas Diagnóstico');

        // Estilos
        $titleStyle = [
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']], // Dark Blue
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ];

        $infoStyle = [
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']], // Light Blue
        ];

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']], // Royal Blue
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];

        $borderStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
        ];

        // Título principal
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'Smart Crea Cities - Diagnóstico da Trilha Formativa dos 3E\'s');
        $sheet->getStyle('A1:E1')->applyFromArray($titleStyle);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Informações da Submissão
        $sheet->setCellValue('A3', 'Município:');
        $sheet->setCellValue('B3', $submission->municipio_nome);
        $sheet->setCellValue('A4', 'Protocolo:');
        $sheet->setCellValue('B4', $submission->protocolo);
        $sheet->setCellValue('D3', 'Regional CREA-PR:');
        $sheet->setCellValue('E3', $submission->regional_creapr);
        $sheet->setCellValue('D4', 'Mais Engenharia:');
        $sheet->setCellValue('E4', $submission->faz_parte_mais_engenharia ? 'Sim' : 'Não');
        $sheet->getStyle('A3:E4')->getFont()->setBold(true);

        // Cabeçalhos das Colunas
        $headers = ['Categoria', 'Questão', 'Tipo', 'Resposta do Município', 'Pontuação Obtida'];
        $sheet->fromArray($headers, null, 'A6');
        $sheet->getStyle('A6:E6')->applyFromArray($headerStyle);
        $sheet->getRowDimension(6)->setRowHeight(24);

        $row = 7;
        foreach ($questions as $q) {
            $answer = DiagnosticAnswer::where('submission_id', $submission->id)
                ->where('diagnostic_question_id', $q->id)
                ->first();

            $formattedVal = $this->getFormattedAnswer($answer);
            $points = $answer ? (float)$answer->points_earned : 0.0;

            $sheet->setCellValue('A' . $row, $this->getCategoryName($q->category));
            $sheet->setCellValue('B' . $row, $q->question);
            $sheet->setCellValue('C' . $row, ucfirst(str_replace('_', ' ', $q->type)));
            $sheet->setCellValue('D' . $row, $formattedVal);
            $sheet->setCellValue('E' . $row, $points);

            $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray($borderStyle);
            $row++;
        }

        // Totais e Score
        $row += 2;
        $sheet->setCellValue('A' . $row, 'PONTUAÇÕES POR EIXO:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Eixo Estímulo:');
        $sheet->setCellValue('B' . $row, $submission->pontuacao_estimulo ?? 0);
        $row++;
        $sheet->setCellValue('A' . $row, 'Eixo Educação:');
        $sheet->setCellValue('B' . $row, $submission->pontuacao_educacao ?? 0);
        $row++;
        $sheet->setCellValue('A' . $row, 'Eixo Estruturas:');
        $sheet->setCellValue('B' . $row, $submission->pontuacao_estruturas ?? 0);
        $row++;
        $sheet->setCellValue('A' . $row, 'SCORE TOTAL:');
        $sheet->setCellValue('B' . $row, $submission->getTotalScore());
        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);

        // Configuração de Larguras das colunas
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'diagnostico_' . strtolower(str_replace(' ', '_', $submission->municipio_nome)) . '_' . date('Ymd_His') . '.xlsx';

        return response()->stream(
            function() use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

    /**
     * Exporta as respostas de um único município para PDF (.pdf)
     */
    public function exportAnswersPdf(Submission $submission)
    {
        $questions = DiagnosticQuestion::where('is_active', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get();

        $answersGrouped = [];
        foreach ($questions as $q) {
            $answer = DiagnosticAnswer::where('submission_id', $submission->id)
                ->where('diagnostic_question_id', $q->id)
                ->first();

            $answersGrouped[$q->category][] = [
                'question' => $q->question,
                'type' => $q->type,
                'formatted' => $this->getFormattedAnswer($answer),
                'points' => $answer ? $answer->points_earned : 0.0
            ];
        }

        $pdf = Pdf::loadView('admin.exports.answers_pdf', [
            'submission' => $submission,
            'answersGrouped' => $answersGrouped,
            'controller' => $this
        ]);

        $filename = 'diagnostico_' . strtolower(str_replace(' ', '_', $submission->municipio_nome)) . '_' . date('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Exporta as respostas consolidadas de TODOS os municípios ativos para Excel (.xlsx)
     */
    public function exportConsolidatedAnswersXlsx()
    {
        $submissions = Submission::active()
            ->orderBy('municipio_nome')
            ->get();

        $questions = DiagnosticQuestion::where('is_active', true)
            ->orderBy('category')
            ->orderBy('order')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Matriz Consolidada');

        // Estilos
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']], // Dark Blue
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        $borderStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
        ];

        // Cabeçalhos Básicos
        $headers = [
            'Município',
            'Protocolo',
            'Regional',
            'Mais Engenharia',
            'Pontos Estímulo',
            'Pontos Educação',
            'Pontos Estruturas',
            'Score Total',
        ];

        // Adiciona as perguntas como colunas dinâmicas
        foreach ($questions as $idx => $q) {
            $catPrefix = strtoupper(substr($q->category, 0, 3));
            $headers[] = $catPrefix . ($idx + 1) . ': ' . $q->question;
        }

        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:' . $this->getColLetter(count($headers)) . '1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Preenche os dados
        $row = 2;
        foreach ($submissions as $sub) {
            $rowData = [
                $sub->municipio_nome,
                $sub->protocolo,
                $sub->regional_creapr,
                $sub->faz_parte_mais_engenharia ? 'Sim' : 'Não',
                $sub->pontuacao_estimulo ?? 0,
                $sub->pontuacao_educacao ?? 0,
                $sub->pontuacao_estruturas ?? 0,
                $sub->getTotalScore(),
            ];

            // Busca a resposta para cada uma das perguntas dinâmicas
            foreach ($questions as $q) {
                $answer = DiagnosticAnswer::where('submission_id', $sub->id)
                    ->where('diagnostic_question_id', $q->id)
                    ->first();
                $rowData[] = $this->getFormattedAnswer($answer);
            }

            $sheet->fromArray($rowData, null, 'A' . $row);
            $sheet->getStyle('A' . $row . ':' . $this->getColLetter(count($headers)) . $row)->applyFromArray($borderStyle);
            $row++;
        }

        // Auto-dimensionar colunas
        $lastCol = $this->getColLetter(count($headers));
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'consolidado_smart_crea_' . date('Ymd_His') . '.xlsx';

        return response()->stream(
            function() use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }

    /**
     * Exporta a matriz consolidada / geral para PDF (.pdf) em formato paisagem (Landscape)
     */
    public function exportConsolidatedAnswersPdf()
    {
        $submissions = Submission::active()
            ->orderBy('municipio_nome')
            ->get();

        $questionsCount = DiagnosticQuestion::where('is_active', true)->count();

        $pdf = Pdf::loadView('admin.exports.consolidated_pdf', [
            'submissions' => $submissions,
            'questionsCount' => $questionsCount,
        ]);

        $pdf->setPaper('a4', 'landscape');

        $filename = 'relatorio_consolidado_' . date('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Utilitário para converter índice de coluna numérica em Letras Excel (ex: 1->A, 28->AB)
     */
    private function getColLetter($num)
    {
        $numeric = ($num - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($num - 1) / 26);
        if ($num2 > 0) {
            return $this->getColLetter($num2) . $letter;
        } else {
            return $letter;
        }
    }
}
