<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manifestações | Smart Crea Cities</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        ← Dashboard
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
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Manifestações de Interesse</h1>
                <p class="text-gray-600">Gerencie e analise as submissões dos municípios</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.consolidado.exportXlsx') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition text-sm flex items-center gap-1.5">
                    📊 Matriz Geral (Excel)
                </a>
                <a href="{{ route('admin.consolidado.exportPdf') }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition text-sm flex items-center gap-1.5">
                    📄 Relatório Geral (PDF)
                </a>
                <a href="{{ route('admin.submissoes.export', request()->query()) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition text-sm flex items-center gap-1.5">
                    📥 Exportar CSV
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-6 mb-8 rounded-lg shadow-md">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-semibold text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Filtros</h2>
            <form method="GET" action="{{ route('admin.submissoes.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Município</label>
                    <input type="text" name="municipio" value="{{ request('municipio') }}" placeholder="Nome do município" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Em Análise</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovado</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitado</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Regional</label>
                    <select name="regional" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todas</option>
                        @foreach($regionais as $regional)
                            <option value="{{ $regional }}" {{ request('regional') === $regional ? 'selected' : '' }}>{{ $regional }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mais Engenharia</label>
                    <select name="mais_engenharia" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos</option>
                        <option value="sim" {{ request('mais_engenharia') === 'sim' ? 'selected' : '' }}>Sim</option>
                        <option value="nao" {{ request('mais_engenharia') === 'nao' ? 'selected' : '' }}>Não</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Exibição Ativos</label>
                    <select name="status_ativo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="ativos" {{ request('status_ativo') === 'ativos' || !request('status_ativo') ? 'selected' : '' }}>Apenas Ativos</option>
                        <option value="inativos" {{ request('status_ativo') === 'inativos' ? 'selected' : '' }}>Apenas Inativos</option>
                        <option value="todos" {{ request('status_ativo') === 'todos' ? 'selected' : '' }}>Todos</option>
                    </select>
                </div>
                
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.submissoes.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg text-center">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabela de Submissões -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Protocolo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Município</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Regional</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">População</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mais Eng.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($submissions as $sub)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-semibold text-blue-600">
                                {{ $sub->protocolo }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sub->municipio_nome }}
                                @if(!$sub->is_active)
                                    <span class="ml-1 px-1.5 py-0.5 text-[10px] font-bold rounded bg-red-100 text-red-800">Inativo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $sub->regional_creapr }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ number_format($sub->habitantes_num, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($sub->faz_parte_mais_engenharia)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sim</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Não</span>
                                @endif
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($sub->getTotalScore() > 0)
                                    <span class="font-bold text-purple-600">{{ $sub->getTotalScore() }}/300</span>
                                @else
                                    <span class="text-gray-400">-</span>
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
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-semibold">Nenhuma manifestação encontrada</p>
                                <p class="text-sm">Tente ajustar os filtros ou aguarde novas submissões</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($submissions->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                {{ $submissions->links() }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>

