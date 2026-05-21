<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Consolidado Smart Crea Cities</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #334155;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header table {
            width: 100%;
        }
        .logo-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
        }
        .logo-subtitle {
            font-size: 9px;
            color: #64748b;
            margin: 1px 0 0 0;
        }
        .doc-title {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            color: #2563eb;
            margin: 0;
        }
        .summary-grid {
            margin-bottom: 20px;
        }
        .summary-grid table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }
        .summary-card-val {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
            margin-top: 2px;
        }
        .consolidated-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .consolidated-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #1e3a8a;
            font-size: 9px;
            text-transform: uppercase;
        }
        .consolidated-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .consolidated-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .total-row {
            background-color: #f1f5f9 !important;
            font-weight: bold;
            border-top: 2px solid #cbd5e1;
        }
        .badge-approved {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-pending {
            background-color: #fef9c3;
            color: #854d0e;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 25px;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
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
                    <h2 class="doc-title">Matriz Consolidada de Indicadores</h2>
                    <p style="font-size: 8px; color: #64748b; margin: 1px 0 0 0;">Relatório Geral dos Municípios | Gerado em: {{ date('d/m/Y H:i') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Metricas Consolidadas Rápida -->
    <div class="summary-grid">
        <table style="width: 100%;">
            <tr>
                <td style="width: 25%; padding-right: 10px;">
                    <div class="summary-card">
                        <div style="font-weight: bold; color: #64748b; font-size: 8px; text-transform: uppercase;">Total Municípios Ativos</div>
                        <div class="summary-card-val">{{ $submissions->count() }}</div>
                    </div>
                </td>
                <td style="width: 25%; padding-right: 10px; padding-left: 10px;">
                    <div class="summary-card">
                        <div style="font-weight: bold; color: #64748b; font-size: 8px; text-transform: uppercase;">Média Geral de Score</div>
                        <div class="summary-card-val">
                            {{ number_format($submissions->avg(fn($s) => $s->getTotalScore()), 2, ',', '.') }} pts
                        </div>
                    </div>
                </td>
                <td style="width: 25%; padding-right: 10px; padding-left: 10px;">
                    <div class="summary-card">
                        <div style="font-weight: bold; color: #64748b; font-size: 8px; text-transform: uppercase;">Adesão Mais Engenharia</div>
                        <div class="summary-card-val">
                            {{ $submissions->where('faz_parte_mais_engenharia', true)->count() }}
                        </div>
                    </div>
                </td>
                <td style="width: 25%; padding-left: 10px;">
                    <div class="summary-card">
                        <div style="font-weight: bold; color: #64748b; font-size: 8px; text-transform: uppercase;">Diagnósticos Completos</div>
                        <div class="summary-card-val">
                            {{ $submissions->filter(fn($s) => $s->allDiagnosticsCompleted())->count() }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tabela Geral -->
    <table class="consolidated-table">
        <thead>
            <tr>
                <th style="width: 25%;">Município</th>
                <th style="width: 10%;">Protocolo</th>
                <th style="width: 15%;">Regional CREA-PR</th>
                <th style="width: 10%;">Mais Engenharia</th>
                <th style="width: 10%; text-align: right;">Eixo Estímulo</th>
                <th style="width: 10%; text-align: right;">Eixo Educação</th>
                <th style="width: 10%; text-align: right;">Eixo Estruturas</th>
                <th style="width: 10%; text-align: right;">Score Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($submissions as $sub)
                <tr>
                    <td><strong>{{ $sub->municipio_nome }}</strong></td>
                    <td style="color: #64748b;">{{ $sub->protocolo }}</td>
                    <td>{{ $sub->regional_creapr }}</td>
                    <td style="text-align: center;">
                        <span class="{{ $sub->faz_parte_mais_engenharia ? 'badge-approved' : 'badge-pending' }}">
                            {{ $sub->faz_parte_mais_engenharia ? 'Sim' : 'Não' }}
                        </span>
                    </td>
                    <td style="text-align: right;">{{ number_format($sub->pontuacao_estimulo ?? 0, 2, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($sub->pontuacao_educacao ?? 0, 2, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format($sub->pontuacao_estruturas ?? 0, 2, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold; color: #1e3a8a;">
                        {{ number_format($sub->getTotalScore(), 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            
            <!-- Linha de Média Geral -->
            <tr class="total-row">
                <td colspan="4">MÉDIA GERAL DOS ATIVOS</td>
                <td style="text-align: right;">
                    {{ number_format($submissions->avg('pontuacao_estimulo') ?? 0, 2, ',', '.') }}
                </td>
                <td style="text-align: right;">
                    {{ number_format($submissions->avg('pontuacao_educacao') ?? 0, 2, ',', '.') }}
                </td>
                <td style="text-align: right;">
                    {{ number_format($submissions->avg('pontuacao_estruturas') ?? 0, 2, ',', '.') }}
                </td>
                <td style="text-align: right; color: #1e3a8a;">
                    {{ number_format($submissions->avg(fn($s) => $s->getTotalScore()), 2, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        © {{ date('Y') }} CREA-PR - Smart Crea Cities - Trilha Formativa dos 3E's. Relatório Consolidado para uso Administrativo.
    </div>
</body>
</html>
