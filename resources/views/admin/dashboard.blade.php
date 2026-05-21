<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin | Smart Crea Cities</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @include('partials.favicons')
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-22 md:h-26 lg:h-30">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('assets/img/card-smart-crea-cities-negativo.png') }}" alt="Smart Crea Cities" class="h-20 sm:h-24 md:h-28 w-auto object-contain">
                    <div class="border-l border-gray-300 h-10"></div>
                    <div>
                        <p class="text-sm text-gray-600">Painel</p>
                        <p class="font-bold text-blue-900">Administrativo CREA-PR</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.submissoes.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Submissões
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.emails.create') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center gap-1">
                        ✉ Compor E-mail
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.questions.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Questões
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.events.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Eventos
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.repository.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Repositório
                    </a>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrador</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Título -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard - Smart Crea Cities</h1>
            <p class="text-gray-600">Visão geral da Trilha Formativa dos 3E's</p>
        </div>

        <!-- Alertas de Feedback -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Painel de Controle de Inscrições e Ações Avançadas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Card Controle de Inscrições -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.334 4z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0011 16V8a1 1 0 00-1.6-.8l-5.334 4z" />
                        </svg>
                        Inscrições de Municípios
                    </h2>
                    <p class="text-sm text-gray-500 mt-2">
                        Controle o período de envio de manifestações de interesse dos municípios interessados.
                    </p>
                </div>
                
                <div class="mt-6 flex items-center justify-between gap-4 border-t pt-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $registrationsBlocked ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        <span class="w-2 h-2 mr-1.5 rounded-full {{ $registrationsBlocked ? 'bg-red-500 animate-pulse' : 'bg-green-500' }}"></span>
                        {{ $registrationsBlocked ? 'Bloqueadas' : 'Abertas' }}
                    </span>

                    <form action="{{ route('admin.settings.toggle-registration') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Deseja realmente {{ $registrationsBlocked ? 'ABRIR' : 'BLOQUEAR' }} o sistema de inscrições?')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-semibold rounded-lg shadow-sm text-white transition-all duration-300 {{ $registrationsBlocked ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} focus:outline-none">
                            {{ $registrationsBlocked ? 'Permitir Inscrições' : 'Bloquear Inscrições' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card Campanhas de E-mail -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5" />
                        </svg>
                        Campanhas de E-mail
                    </h2>
                    <p class="text-sm text-gray-500 mt-2">
                        Envie e-mails com credenciais temporárias automáticas, links da plataforma e tags personalizadas.
                    </p>
                </div>
                
                <div class="mt-6 border-t pt-4 text-right">
                    <a href="{{ route('admin.emails.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold rounded-lg text-white transition shadow shadow-indigo-600/10">
                        ✉ Abrir Painel de Envio
                    </a>
                </div>
            </div>

            <!-- Card Exportação Geral Consolidada -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Matriz Geral Consolidada
                    </h2>
                    <p class="text-sm text-gray-500 mt-2">
                        Exporte relatórios agregados com desempenho de todos os municípios por eixo formativo.
                    </p>
                </div>
                
                <div class="mt-6 border-t pt-4 flex gap-2">
                    <a href="{{ route('admin.consolidado.exportXlsx') }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-xs font-semibold rounded-lg text-white transition shadow shadow-emerald-600/10">
                        📊 Excel
                    </a>
                    <a href="{{ route('admin.consolidado.exportPdf') }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-rose-600 hover:bg-rose-700 text-xs font-semibold rounded-lg text-white transition shadow shadow-rose-600/10">
                        📄 PDF (A4)
                    </a>
                </div>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total de Submissões -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Total de Manifestações</p>
                        <p class="text-3xl font-bold text-blue-900 mt-2">{{ $totalSubmissoes }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pendentes -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Aguardando Análise</p>
                        <p class="text-3xl font-bold text-yellow-900 mt-2">{{ $pendentes }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Aprovadas -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Aprovadas</p>
                        <p class="text-3xl font-bold text-green-900 mt-2">{{ $aprovadas }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Diagnósticos Completos -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Diagnósticos Completos</p>
                        <p class="text-3xl font-bold text-purple-900 mt-2">{{ $diagnosticosCompletos }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mais Engenharia e Médias -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Mais Engenharia -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Programa Mais Engenharia</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">Participantes</span>
                        <span class="font-bold text-2xl text-green-600">{{ $maisEngenharia }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: {{ $totalSubmissoes > 0 ? ($maisEngenharia / $totalSubmissoes) * 100 : 0 }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Não Participantes</span>
                        <span class="font-semibold text-gray-700">{{ $naoMaisEngenharia }}</span>
                    </div>
                </div>
            </div>

            <!-- Médias de Pontuação -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Médias de Pontuação (0-100)</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-gray-700">Estímulo</span>
                            <span class="font-bold text-blue-600">{{ number_format($mediaPontuacaoEstimulo, 1) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $mediaPontuacaoEstimulo }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-gray-700">Educação</span>
                            <span class="font-bold text-green-600">{{ number_format($mediaPontuacaoEducacao, 1) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $mediaPontuacaoEducacao }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-gray-700">Estruturas</span>
                            <span class="font-bold text-purple-600">{{ number_format($mediaPontuacaoEstruturas, 1) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $mediaPontuacaoEstruturas }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Status das Submissões -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Status das Manifestações</h2>
                <canvas id="statusChart"></canvas>
            </div>

            <!-- Distribuição por Regional -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Top 5 Regionais</h2>
                <canvas id="regionalChart"></canvas>
            </div>
        </div>

        <!-- Próximos Eventos -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Próximos Eventos do Programa</h2>
                <a href="{{ route('admin.events.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                    Gerenciar Eventos →
                </a>
            </div>
            
            @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
            <div class="space-y-3">
                @foreach($upcomingEvents->take(5) as $event)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $event->event_date->format('d') }}</div>
                            <div class="text-xs text-gray-500 uppercase">{{ $event->event_date->format('M') }}</div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $event->title }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                @if($event->event_time)
                                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($event->event_time)->format('H:i') }}</span>
                                @endif
                                @if($event->location)
                                <span class="text-sm text-gray-400">•</span>
                                <span class="text-sm text-gray-600">{{ $event->location }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $event->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $event->is_published ? 'Publicado' : 'Rascunho' }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="font-medium">Nenhum evento programado</p>
                <a href="{{ route('admin.events.create') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm mt-2 inline-block">
                    Criar primeiro evento →
                </a>
            </div>
            @endif
        </div>

        <!-- Últimas Submissões -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Últimas Manifestações</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Protocolo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Município</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Regional</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ultimasSubmissoes as $sub)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-semibold text-blue-600">
                                {{ $sub->protocolo }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sub->municipio_nome }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $sub->regional_creapr }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($sub->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendente</span>
                                @elseif($sub->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aprovado</span>
                                @elseif($sub->status === 'under_review')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Em Análise</span>
                                @elseif($sub->status === 'rejected')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejeitado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $sub->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.submissoes.show', $sub) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Ver Detalhes →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('admin.submissoes.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                    Ver Todas as Manifestações →
                </a>
            </div>
        </div>
    </div>

    <script>
        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pendentes', 'Aprovadas', 'Em Análise', 'Rejeitadas'],
                datasets: [{
                    data: [{{ $pendentes }}, {{ $aprovadas }}, {{ $emAnalise }}, {{ $rejeitadas }}],
                    backgroundColor: ['#eab308', '#22c55e', '#3b82f6', '#ef4444'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Regional Chart
        const regionalCtx = document.getElementById('regionalChart').getContext('2d');
        new Chart(regionalCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($porRegional->take(5)->pluck('regional_creapr')) !!},
                datasets: [{
                    label: 'Manifestações',
                    data: {!! json_encode($porRegional->take(5)->pluck('total')) !!},
                    backgroundColor: '#3b82f6',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

