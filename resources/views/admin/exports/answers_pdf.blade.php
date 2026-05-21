<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Diagnóstico - {{ $submission->municipio_nome }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #334155;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 3px solid #1d4ed8;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
        }
        .logo-title {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
        }
        .logo-subtitle {
            font-size: 10px;
            color: #64748b;
            margin: 2px 0 0 0;
        }
        .doc-title {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #1d4ed8;
            margin: 0;
        }
        .metadata-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }
        .metadata-box table {
            width: 100%;
        }
        .metadata-box td {
            padding: 4px 10px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e3a8a;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
            margin-top: 25px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .answers-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .answers-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #1e3a8a;
        }
        .answers-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .answers-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .score-summary {
            margin-top: 30px;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 15px;
        }
        .score-summary table {
            width: 100%;
        }
        .score-summary td {
            padding: 5px 15px;
        }
        .total-score-val {
            font-size: 22px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <h1 class="logo-title">Smart Crea Cities</h1>
                    <p class="logo-subtitle">CREA-PR - Conselho Regional de Engenharia e Agronomia do Paraná</p>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <h2 class="doc-title">Diagnóstico da Trilha Formativa 3E's</h2>
                    <p style="font-size: 9px; color: #64748b; margin: 2px 0 0 0;">Gerado em: {{ date('d/m/Y H:i') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Metadata Box -->
    <div class="metadata-box">
        <table>
            <tr>
                <td style="width: 15%;"><strong>Município:</strong></td>
                <td style="width: 35%;">{{ $submission->municipio_nome }}</td>
                <td style="width: 20%;"><strong>Regional CREA-PR:</strong></td>
                <td style="width: 30%;">{{ $submission->regional_creapr }}</td>
            </tr>
            <tr>
                <td><strong>Protocolo:</strong></td>
                <td>{{ $submission->protocolo }}</td>
                <td><strong>Mais Engenharia:</strong></td>
                <td>{{ $submission->faz_parte_mais_engenharia ? 'Sim' : 'Não' }}</td>
            </tr>
            <tr>
                <td><strong>Responsável:</strong></td>
                <td>{{ $submission->responsavel_nome ?? '-' }}</td>
                <td><strong>E-mail Contato:</strong></td>
                <td>{{ $submission->responsavel_email ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Habitantes:</strong></td>
                <td>{{ number_format($submission->habitantes_num, 0, ',', '.') }} habitantes</td>
                <td><strong>Data Envio:</strong></td>
                <td>{{ $submission->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <!-- Categorias / Eixos -->
    @foreach(['estimulo' => 'Eixo Estímulo', 'educacao' => 'Eixo Educação', 'estruturas' => 'Eixo Estruturas'] as $key => $title)
        @if(isset($answersGrouped[$key]))
            <div class="section-title">{{ $title }}</div>
            <table class="answers-table">
                <thead>
                    <tr>
                        <th style="width: 55%;">Questão / Critério</th>
                        <th style="width: 15%;">Tipo</th>
                        <th style="width: 20%;">Resposta do Município</th>
                        <th style="width: 10%; text-align: right;">Pontos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($answersGrouped[$key] as $answer)
                        <tr>
                            <td><strong>{{ $answer['question'] }}</strong></td>
                            <td style="color: #64748b; font-size: 10px;">{{ ucfirst(str_replace('_', ' ', $answer['type'])) }}</td>
                            <td>{{ $answer['formatted'] }}</td>
                            <td style="text-align: right; font-weight: bold;">{{ number_format($answer['points'], 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <div class="page-break"></div>

    <div class="header">
        <table>
            <tr>
                <td>
                    <h1 class="logo-title">Smart Crea Cities</h1>
                    <p class="logo-subtitle">CREA-PR - Conselho Regional de Engenharia e Agronomia do Paraná</p>
                </td>
                <td style="text-align: right; vertical-align: middle;">
                    <h2 class="doc-title">Resumo e Score do Diagnóstico</h2>
                </td>
            </tr>
        </table>
    </div>

    <!-- Score Summary Card -->
    <div class="score-summary">
        <h3 style="margin-top: 0; color: #1e3a8a; font-size: 14px; border-bottom: 1px solid #bfdbfe; padding-bottom: 6px;">
            Consolidado de Desempenho
        </h3>
        <table>
            <tr>
                <td style="width: 60%; font-size: 12px;"><strong>Eixo Estímulo:</strong></td>
                <td style="text-align: right; font-size: 12px; font-weight: bold;">
                    {{ number_format($submission->pontuacao_estimulo ?? 0, 2, ',', '.') }} / 100,00
                </td>
            </tr>
            <tr>
                <td style="font-size: 12px;"><strong>Eixo Educação:</strong></td>
                <td style="text-align: right; font-size: 12px; font-weight: bold;">
                    {{ number_format($submission->pontuacao_educacao ?? 0, 2, ',', '.') }} / 100,00
                </td>
            </tr>
            <tr>
                <td style="font-size: 12px;"><strong>Eixo Estruturas:</strong></td>
                <td style="text-align: right; font-size: 12px; font-weight: bold;">
                    {{ number_format($submission->pontuacao_estruturas ?? 0, 2, ',', '.') }} / 100,00
                </td>
            </tr>
            <tr style="border-top: 1px solid #bfdbfe;">
                <td style="font-size: 14px; padding-top: 10px; color: #1e3a8a;"><strong>SCORE TOTAL ACUMULADO:</strong></td>
                <td style="text-align: right; padding-top: 10px;" class="total-score-val">
                    {{ number_format($submission->getTotalScore(), 2, ',', '.') }} pts
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 40px; text-align: center; color: #64748b;">
        <p style="font-size: 10px; margin-bottom: 25px;">Este relatório comprova a participação e as respostas enviadas pelo município na plataforma Smart Crea Cities.</p>
        <div style="border-top: 1px solid #cbd5e1; width: 250px; margin: 0 auto; padding-top: 5px; font-weight: bold; color: #334155;">
            Equipe Técnica de Avaliação
        </div>
        <div style="font-size: 9px; color: #94a3b8; margin-top: 2px;">Smart Crea Cities CREA-PR</div>
    </div>

    <div class="footer">
        © {{ date('Y') }} CREA-PR - Smart Crea Cities - Trilha Formativa dos 3E's. Todos os direitos reservados.
    </div>
</body>
</html>
