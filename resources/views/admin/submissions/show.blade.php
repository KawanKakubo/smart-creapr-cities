<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalhes: {{ $submission->municipio_nome }} | Smart Crea Cities</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                        <p class="text-sm text-gray-600">Manifestação</p>
                        <p class="font-bold text-blue-900">{{ $submission->municipio_nome }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.submissoes.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        ← Voltar para Lista
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

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-8 rounded-lg shadow-md">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="font-semibold text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Cabeçalho -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $submission->municipio_nome }}</h1>
                    <p class="text-gray-600">Protocolo: <span class="font-mono font-bold text-blue-600">{{ $submission->protocolo }}</span></p>
                    <p class="text-sm text-gray-500 mt-1">Registrado em: {{ $submission->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    @if($submission->status === 'pending')
                        <span class="px-4 py-2 text-sm font-bold rounded-full bg-yellow-100 text-yellow-800">PENDENTE</span>
                    @elseif($submission->status === 'approved')
                        <span class="px-4 py-2 text-sm font-bold rounded-full bg-green-100 text-green-800">APROVADO</span>
                    @elseif($submission->status === 'under_review')
                        <span class="px-4 py-2 text-sm font-bold rounded-full bg-blue-100 text-blue-800">EM ANÁLISE</span>
                    @elseif($submission->status === 'rejected')
                        <span class="px-4 py-2 text-sm font-bold rounded-full bg-red-100 text-red-800">REJEITADO</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna Principal --><div class="lg:col-span-2 space-y-8">
                <!-- Informações do Município -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Informações do Município
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">População</p>
                            <p class="font-semibold text-gray-900">{{ number_format($submission->habitantes_num, 0, ',', '.') }} habitantes</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Regional CREA-PR</p>
                            <p class="font-semibold text-gray-900">{{ $submission->regional_creapr }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600">Setores Econômicos</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @php
                                    $setores = $submission->setores_economicos;
                                    if (is_string($setores)) {
                                        $setores = json_decode($setores, true) ?? [];
                                    }
                                    $setores = $setores ?? [];
                                @endphp
                                @foreach($setores as $setor)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">{{ $setor }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600">Secretarias</p>
                            <ul class="mt-1 text-sm text-gray-900 space-y-1">
                                @if($submission->secretaria_inovacao)
                                    <li>• Inovação: {{ $submission->secretaria_inovacao }}</li>
                                @endif
                                @if($submission->secretaria_tecnologia_smart)
                                    <li>• Tecnologia/Smart: {{ $submission->secretaria_tecnologia_smart }}</li>
                                @endif
                                @if($submission->secretaria_engenharia)
                                    <li>• Engenharia: {{ $submission->secretaria_engenharia }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Programa Mais Engenharia -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Programa Mais Engenharia</h2>
                        
                        <form action="{{ route('admin.submissoes.updateMaisEngenharia', $submission) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            @if($submission->faz_parte_mais_engenharia)
                                <button type="submit"
                                        onclick="return confirm('Deseja realmente REMOVER este município do programa Mais Engenharia? A conta de acesso associada ao responsável será removida.')"
                                        class="inline-flex items-center px-3 py-1.5 border border-red-300 text-xs font-semibold rounded-lg text-red-700 bg-red-50 hover:bg-red-100 transition focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remover do Programa
                                </button>
                            @else
                                <button type="submit"
                                        onclick="return confirm('Deseja realmente ADICIONAR este município ao programa Mais Engenharia? Isso gerará credenciais de acesso e enviará um e-mail para o responsável.')"
                                        class="inline-flex items-center px-3 py-1.5 border border-green-300 text-xs font-semibold rounded-lg text-green-700 bg-green-50 hover:bg-green-100 transition focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    Incluir no Programa
                                </button>
                            @endif
                        </form>
                    </div>

                    @if($submission->faz_parte_mais_engenharia)
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                            <p class="font-semibold text-green-800">✓ Município faz parte do programa Mais Engenharia</p>
                        </div>
                        
                        @if($submission->responsavel_nome)
                            <div class="mt-4">
                                <h3 class="font-semibold text-gray-900 mb-3">Responsável</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Nome</p>
                                        <p class="font-semibold text-gray-900">{{ $submission->responsavel_nome }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">CPF</p>
                                        <p class="font-semibold text-gray-900">{{ $submission->responsavel_cpf }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Email</p>
                                        <p class="font-semibold text-gray-900">{{ $submission->responsavel_email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Telefone</p>
                                        <p class="font-semibold text-gray-900">{{ $submission->responsavel_telefone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Órgão</p>
                                        <p class="font-semibold text-gray-900">{{ $submission->responsavel_orgao }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Função</p>
                                        <p class="font-semibold text-gray-900">{{ $submission->responsavel_funcao }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if($submission->prefeito_nome)
                            <div class="mt-6">
                                <h3 class="font-semibold text-gray-900 mb-3">Prefeito</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Nome</p>
                                        <p class="font-semibold text-gray-900">{{ $submission->prefeito_nome }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Mandato</p>
                                        <p class="font-semibold text-gray-900">{{ $submission->prefeito_mandato }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
                            <p class="font-semibold text-gray-700">Município NÃO faz parte do programa Mais Engenharia</p>
                            <p class="text-sm text-gray-600 mt-1">Aguardando futuro contato do CREA-PR</p>
                        </div>
                    @endif
                </div>

                <!-- Comitê Smart Crea -->
                @if($submission->committeeMembers->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Comitê Smart Crea ({{ $submission->committeeMembers->count() }}/5)</h2>
                    <div class="space-y-3">
                        @foreach($submission->committeeMembers as $member)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <p class="font-semibold text-gray-900">{{ $member->nome }}</p>
                                <div class="grid grid-cols-2 gap-2 mt-2 text-sm text-gray-600">
                                    <p>Email: {{ $member->email }}</p>
                                    <p>Telefone: {{ $member->telefone }}</p>
                                    <p>Órgão: {{ $member->orgao }}</p>
                                    <p>Cargo: {{ $member->cargo }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Diagnósticos -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Diagnósticos de Maturidade</h2>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Estímulo -->
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-gray-600">Estímulo</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $submission->pontuacao_estimulo ?? 0 }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($submission->diagnostico_estimulo_concluido_em)
                                    Concluído em {{ $submission->diagnostico_estimulo_concluido_em->format('d/m/Y') }}
                                @elseif($submission->diagnostico_estimulo_iniciado_em)
                                    Iniciado
                                @else
                                    Não iniciado
                                @endif
                            </p>
                        </div>

                        <!-- Educação -->
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-sm text-gray-600">Educação</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $submission->pontuacao_educacao ?? 0 }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($submission->diagnostico_educacao_concluido_em)
                                    Concluído em {{ $submission->diagnostico_educacao_concluido_em->format('d/m/Y') }}
                                @elseif($submission->diagnostico_educacao_iniciado_em)
                                    Iniciado
                                @else
                                    Não iniciado
                                @endif
                            </p>
                        </div>

                        <!-- Estruturas -->
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-sm text-gray-600">Estruturas</p>
                            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $submission->pontuacao_estruturas ?? 0 }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($submission->diagnostico_estruturas_concluido_em)
                                    Concluído em {{ $submission->diagnostico_estruturas_concluido_em->format('d/m/Y') }}
                                @elseif($submission->diagnostico_estruturas_iniciado_em)
                                    Iniciado
                                @else
                                    Não iniciado
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Score Total</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $submission->getTotalScore() }} <span class="text-2xl text-gray-500">/ 300</span></p>
                    </div>
                </div>

                <!-- Detalhamento das Respostas -->
                @if($submission->diagnosticAnswers->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Detalhamento das Respostas ({{ $submission->diagnosticAnswers->count() }} respostas)
                    </h2>
                    
                    @php
                        $answersByCategory = $submission->diagnosticAnswers->groupBy('category');
                    @endphp

                    @foreach(['estimulo' => 'Estímulo', 'educacao' => 'Educação', 'estruturas' => 'Estruturas'] as $categoryKey => $categoryName)
                        @if(isset($answersByCategory[$categoryKey]) && $answersByCategory[$categoryKey]->count() > 0)
                            <div class="mb-6 border-t pt-6 first:border-t-0 first:pt-0">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    @if($categoryKey === 'estimulo')
                                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                                    @elseif($categoryKey === 'educacao')
                                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                    @else
                                        <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span>
                                    @endif
                                    {{ $categoryName }}
                                    <span class="ml-2 text-sm font-normal text-gray-500">({{ $answersByCategory[$categoryKey]->count() }} respostas)</span>
                                </h3>
                                
                                <div class="space-y-4">
                                    @foreach($answersByCategory[$categoryKey] as $answer)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                            <div class="flex items-start justify-between mb-2">
                                                <p class="font-semibold text-gray-900 flex-1">{{ $answer->question->question }}</p>
                                                <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full whitespace-nowrap">
                                                    {{ number_format($answer->points_earned, 0) }} pts
                                                </span>
                                            </div>
                                            
                                            <div class="mt-3">
                                                @if($answer->question->type === 'yes_no' || $answer->question->type === 'yes_no_evidence')
                                                    <div class="flex items-center">
                                                        <span class="text-sm text-gray-600 mr-2">Resposta:</span>
                                                        @if($answer->answer_yes_no === 'yes')
                                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">✓ Sim</span>
                                                        @elseif($answer->answer_yes_no === 'no')
                                                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">✗ Não</span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($answer->evidence_url)
                                                        <div class="mt-2 flex items-center text-sm">
                                                            <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                                            </svg>
                                                            <span class="text-gray-600 mr-2">Evidência:</span>
                                                            <a href="{{ $answer->evidence_url }}" target="_blank" class="text-blue-600 hover:underline truncate max-w-md">
                                                                {{ $answer->evidence_url }}
                                                            </a>
                                                        </div>
                                                    @endif

                                                @elseif($answer->question->type === 'checkbox')
                                                    <div>
                                                        <span class="text-sm text-gray-600">Opções selecionadas:</span>
                                                        <div class="flex flex-wrap gap-2 mt-2">
                                                            @if(is_array($answer->answer_checkboxes))
                                                                @foreach($answer->answer_checkboxes as $option)
                                                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 text-sm rounded-full">{{ $option }}</span>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>

                                                @elseif($answer->question->type === 'multiple_input')
                                                    <div>
                                                        <span class="text-sm text-gray-600">Valores informados:</span>
                                                        <div class="mt-2 bg-gray-50 rounded-lg p-3">
                                                            @if(is_array($answer->answer_multiple_input))
                                                                @foreach($answer->answer_multiple_input as $key => $value)
                                                                    <div class="text-sm mb-1">
                                                                        <span class="font-medium text-gray-700">{{ $key }}:</span>
                                                                        <span class="text-gray-900">{{ $value }}</span>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>

                                                @elseif($answer->question->type === 'text')
                                                    <div>
                                                        <span class="text-sm text-gray-600">Resposta:</span>
                                                        <div class="mt-2 bg-gray-50 rounded-lg p-3">
                                                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $answer->answer_text }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="mt-3 pt-3 border-t border-gray-100">
                                                <p class="text-xs text-gray-500">
                                                    ID da Questão: {{ $answer->question->id }} • 
                                                    Tipo: {{ $answer->question->type }} • 
                                                    Peso: {{ $answer->question->weight }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Coluna Lateral - Painel de Revisão -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Painel de Revisão</h2>
                    
                    <form method="POST" action="{{ route('admin.submissoes.updateStatus', $submission) }}" x-data="{ status: '{{ $submission->status }}' }">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alterar Status</label>
                            <select name="status" x-model="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="pending">Pendente</option>
                                <option value="under_review">Em Análise</option>
                                <option value="approved">Aprovado</option>
                                <option value="rejected">Rejeitado</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                            <textarea name="observacao" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Adicione observações sobre esta manifestação...">{{ $submission->status_observacao }}</textarea>
                        </div>

                        <!-- Avisos -->
                        <div x-show="status === 'approved'" class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                            <p class="text-sm text-green-800 font-semibold">✓ Ao aprovar, o município poderá acessar os diagnósticos</p>
                            @if($submission->user)
                                <p class="text-xs text-green-700 mt-1">Email de aprovação será enviado para: {{ $submission->user->email }}</p>
                            @endif
                        </div>

                        <div x-show="status === 'rejected'" class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                            <p class="text-sm text-red-800 font-semibold">⚠ Ao rejeitar, o município não terá acesso à plataforma</p>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                            Salvar Alterações
                        </button>
                    </form>

                    @if($submission->status_observacao)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="font-semibold text-gray-900 mb-2">Observações Anteriores</h3>
                            <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">{{ $submission->status_observacao }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>

