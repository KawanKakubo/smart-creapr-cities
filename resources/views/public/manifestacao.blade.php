<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manifestação de Interesse | Smart Crea Cities</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,600,700,800,900" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .step-indicator {
            transition: all 0.3s ease;
        }
        .step-active {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            scale: 1.1;
        }
        .step-completed {
            background: #10b981;
            color: white;
        }
        .step-inactive {
            background: #e5e7eb;
            color: #6b7280;
        }
        
        /* Header moderno */
        .header-blur {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
        }
        
        /* Botão navbar style */
        .nav-btn {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-btn:hover::after {
            width: 100%;
        }

        .wait-orb {
            position: relative;
            overflow: hidden;
        }

        .wait-orb::after {
            content: '';
            position: absolute;
            inset: -35%;
            background: conic-gradient(from 0deg, transparent 0deg, rgba(59, 130, 246, 0.15) 120deg, rgba(251, 191, 36, 0.3) 210deg, transparent 320deg);
            animation: orbit 2.4s linear infinite;
        }

        .wait-dot {
            width: 0.65rem;
            height: 0.65rem;
            border-radius: 9999px;
            animation: wait-bounce 1.1s infinite ease-in-out;
        }

        .wait-dot:nth-child(2) {
            animation-delay: 0.15s;
        }

        .wait-dot:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes orbit {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes wait-bounce {
            0%, 80%, 100% {
                transform: translateY(0) scale(0.9);
                opacity: 0.45;
            }

            40% {
                transform: translateY(-6px) scale(1);
                opacity: 1;
            }
        }
    </style>
    @include('partials.favicons')
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header Moderno e Compacto -->
    <nav class="fixed w-full top-0 z-50 header-blur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-22 md:h-26 lg:h-30">
                <!-- Logo Simplificada (sem texto secundário) -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/img/smart-crea-cities-negativo.png') }}" 
                             alt="Smart Crea Cities" 
                             class="h-20 sm:h-24 md:h-28 w-auto object-contain">
                    </a>
                </div>
                
                <!-- Botões Desktop -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ asset('assets/pdfs/SEI_CREA-PR - 2376419 - Regulamento.pdf') }}" 
                       target="_blank"
                       class="nav-btn text-white px-2 py-3 font-medium text-sm hover:text-amber-400 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Regulamento
                    </a>
                    <a href="{{ route('login') }}" 
                       class="nav-btn text-white px-2 py-3 font-medium text-sm hover:text-amber-400">
                        Login
                    </a>
                    <a href="{{ route('home') }}" 
                       class="bg-gradient-to-r from-amber-400 to-amber-500 text-slate-900 px-6 py-3 rounded-lg font-bold text-sm hover:from-amber-500 hover:to-amber-600 transition-all shadow-lg hover:shadow-xl hover:scale-105 whitespace-nowrap">
                        Voltar ao Início
                    </a>
                </div>
                
                <!-- Botões Mobile Compactos -->
                <div class="flex md:hidden items-center gap-1.5">
                    <a href="{{ asset('assets/pdfs/SEI_CREA-PR - 2376419 - Regulamento.pdf') }}" 
                       target="_blank"
                       class="text-white px-2.5 py-2.5 rounded-lg font-semibold text-xs hover:text-amber-400 transition-all flex items-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="text-white px-3 py-2.5 rounded-lg font-semibold text-xs hover:text-amber-400 transition-all">
                        Login
                    </a>
                    <a href="{{ route('home') }}" 
                       class="bg-gradient-to-r from-amber-400 to-amber-500 text-slate-900 px-3 py-2.5 rounded-lg font-semibold text-xs whitespace-nowrap">
                        Início
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Formulário Multi-Step -->
    <div class="max-w-4xl mx-auto px-4 pt-28 md:pt-36 lg:pt-40 pb-8" x-data="manifestacaoForm()">
        <div x-show="isSubmitting"
             x-transition.opacity
             class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/55 px-4 backdrop-blur-sm"
             style="display: none;">
            <div class="w-full max-w-2xl overflow-hidden rounded-[2rem] bg-slate-950 text-white shadow-2xl ring-1 ring-white/10">
                <div class="relative overflow-hidden px-6 py-8 sm:px-8">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.32),_transparent_42%),radial-gradient(circle_at_bottom_right,_rgba(251,191,36,0.18),_transparent_30%)]"></div>

                    <div class="relative">
                        <div class="flex items-start gap-4 sm:gap-5">
                            <div class="wait-orb flex h-16 w-16 items-center justify-center rounded-full bg-white/8 ring-1 ring-white/10">
                                <div class="relative z-10 flex h-11 w-11 items-center justify-center rounded-full bg-slate-900">
                                    <svg class="h-6 w-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-blue-200/80" x-text="waitLabel"></p>
                                <h2 class="mt-2 text-2xl font-black leading-tight sm:text-3xl" x-text="waitTitle"></h2>
                                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-200" x-text="waitDescription"></p>

                                <div class="mt-5 flex items-center gap-2">
                                    <span class="wait-dot bg-blue-300"></span>
                                    <span class="wait-dot bg-amber-300"></span>
                                    <span class="wait-dot bg-white"></span>
                                    <span class="ml-2 text-xs font-medium text-slate-300" x-text="waitDurationText()"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 grid gap-3 sm:grid-cols-3">
                            <template x-for="(item, index) in waitChecklist" :key="item.title">
                                <div class="rounded-2xl border px-4 py-4 transition duration-500"
                                     :class="waitStepClass(index)">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-sm font-semibold" x-text="item.title"></p>
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full border text-xs font-bold"
                                              :class="waitBadgeClass(index)">
                                            <template x-if="waitStepState(index) === 'done'">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </template>
                                            <template x-if="waitStepState(index) !== 'done'">
                                                <span x-text="index + 1"></span>
                                            </template>
                                        </span>
                                    </div>
                                    <p class="mt-2 text-xs leading-5 text-slate-300" x-text="item.description"></p>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-200">
                            <span class="font-semibold text-white">Não feche esta página.</span>
                            <span x-text="isMaisEngenharia() ? ' Estamos criando o acesso e preparando o envio das credenciais.' : ' Estamos registrando a manifestação e preparando a confirmação.'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicadores de Step -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <template x-for="(step, index) in steps" :key="index">
                    <div class="flex flex-col items-center flex-1">
                        <div 
                            class="step-indicator w-10 h-10 rounded-full flex items-center justify-center font-bold text-base mb-2"
                            :class="{
                                'step-active': currentStep === index + 1,
                                'step-completed': currentStep > index + 1,
                                'step-inactive': currentStep < index + 1
                            }">
                            <span x-show="currentStep <= index + 1" x-text="index + 1"></span>
                            <svg x-show="currentStep > index + 1" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="text-xs text-center font-medium" :class="currentStep === index + 1 ? 'text-blue-900' : 'text-gray-500'" x-text="step.title"></span>
                    </div>
                </template>
            </div>
        </div>

        <!-- Formulário -->
          <form @submit.prevent="submitForm"
              :class="isSubmitting ? 'pointer-events-none select-none opacity-75' : ''"
              class="bg-white rounded-xl shadow-2xl p-6 md:p-8 transition-opacity duration-200">
            <!-- STEP 1: Dados do Município -->
            <div x-show="currentStep === 1" x-transition>
                <h2 class="text-xl sm:text-2xl font-bold text-blue-900 mb-2">Dados do Município</h2>
                <p class="text-gray-600 mb-8">Informe os dados básicos do seu município</p>

                <div class="space-y-6">
                    <!-- Nome do Município -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nome do Município *</label>
                        <input type="text" x-model="formData.municipio_nome" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Número de Habitantes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Número de Habitantes *</label>
                        <input type="number" x-model.number="formData.habitantes_num" required min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Regional do CREA-PR -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Regional do CREA-PR *</label>
                        <select x-model="formData.regional_creapr" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Selecione a regional</option>
                            <option value="Apucarana">Regional Apucarana</option>
                            <option value="Cascavel">Regional Cascavel</option>
                            <option value="Curitiba">Regional Curitiba</option>
                            <option value="Guarapuava">Regional Guarapuava</option>
                            <option value="Londrina">Regional Londrina</option>
                            <option value="Maringá">Regional Maringá</option>
                            <option value="Pato Branco">Regional Pato Branco</option>
                            <option value="Ponta Grossa">Regional Ponta Grossa</option>
                        </select>
                    </div>

                    <!-- Setores Econômicos (Chips dinâmicos) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Setores Econômicos Relevantes *</label>
                        <p class="text-sm text-gray-500 mb-3">Adicione os principais setores econômicos do município</p>
                        
                        <div class="flex gap-2 mb-3">
                            <input type="text" x-model="newSetor" @keyup.enter.prevent="addSetor"
                                   placeholder="Digite e pressione Enter"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="button" @click="addSetor" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Adicionar
                            </button>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <template x-for="(setor, index) in formData.setores_economicos" :key="index">
                                <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full flex items-center gap-2">
                                    <span x-text="setor"></span>
                                    <button type="button" @click="removeSetor(index)" class="text-blue-600 hover:text-blue-900 font-bold">
                                        ×
                                    </button>
                                </div>
                            </template>
                        </div>
                        <p x-show="formData.setores_economicos.length === 0" class="text-sm text-gray-400 mt-2">Nenhum setor adicionado</p>
                    </div>

                    <!-- Secretarias -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Informe a Secretaria que cuida da pasta de inovação?</label>
                            <input type="text" x-model="formData.secretaria_inovacao"
                                   placeholder="Nome da secretaria responsável pela inovação"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Informe a Secretaria que cuida da pasta de cidade inteligente?</label>
                            <input type="text" x-model="formData.secretaria_tecnologia_smart"
                                   placeholder="Nome da secretaria responsável por cidade inteligente"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Informe a Secretaria que cuida dos projetos de engenharia?</label>
                            <input type="text" x-model="formData.secretaria_engenharia"
                                   placeholder="Nome da secretaria responsável por projetos de engenharia"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 2: Dados do Responsável -->
            <div x-show="currentStep === 2" x-transition>
                <h2 class="text-xl sm:text-2xl font-bold text-blue-900 mb-2">Dados do Responsável</h2>
                <p class="text-sm text-gray-600 mb-6">Informe os dados do responsável pela manifestação</p>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo *</label>
                            <input type="text" x-model="formData.responsavel_nome" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">CPF *</label>
                            <input type="text" x-model="formData.responsavel_cpf" required
                                   placeholder="000.000.000-00" x-mask="999.999.999-99"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone *</label>
                            <input type="text" x-model="formData.responsavel_telefone" required
                                   placeholder="(00) 00000-0000" x-mask="(99) 99999-9999"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">E-mail *</label>
                            <input type="email" x-model="formData.responsavel_email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Órgão *</label>
                            <input type="text" x-model="formData.responsavel_orgao" required
                                   placeholder="Ex: Secretaria de Educação"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Função/Cargo *</label>
                            <input type="text" x-model="formData.responsavel_funcao" required
                                   placeholder="Ex: Secretário de Inovação"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Endereço do Órgão</label>
                            <input type="text" x-model="formData.orgao_endereco"
                                   placeholder="Rua, número, bairro, cidade"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 3: Dados do Prefeito -->
            <div x-show="currentStep === 3" x-transition>
                <h2 class="text-xl sm:text-2xl font-bold text-blue-900 mb-2">Dados do Prefeito</h2>
                <p class="text-sm text-gray-600 mb-6">Informe os dados do prefeito do município</p>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nome Completo *</label>
                            <input type="text" x-model="formData.prefeito_nome" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">CPF *</label>
                            <input type="text" x-model="formData.prefeito_cpf" required
                                   placeholder="000.000.000-00" x-mask="999.999.999-99"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Telefone *</label>
                            <input type="text" x-model="formData.prefeito_telefone" required
                                   placeholder="(00) 00000-0000" x-mask="(99) 99999-9999"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Período de Mandato *</label>
                            <input type="text" x-model="formData.prefeito_mandato" required
                                   placeholder="Ex: 2021-2024"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: Validador "Mais Engenharia" -->
            <div x-show="currentStep === 4" x-transition>
                <h2 class="text-xl sm:text-2xl font-bold text-blue-900 mb-2">Programa "Mais Engenharia"</h2>
                <p class="text-sm text-gray-600 mb-6">Esta informação é essencial para o direcionamento da manifestação</p>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Importante</h3>
                            <p class="mt-2 text-sm text-yellow-700">
                                Municípios que fazem parte do programa "Mais Engenharia" terão acesso completo à plataforma 
                                e poderão realizar o diagnóstico detalhado de maturidade tecnológica. Caso não faça parte, 
                                sua manifestação será registrada para futuras oportunidades.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-lg font-semibold text-gray-700 mb-6">
                        O município faz parte do programa "Mais Engenharia"? *
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Opção SIM -->
                        <label class="cursor-pointer relative group">
                            <input type="radio" x-model="formData.faz_parte_mais_engenharia" value="true" name="mais_engenharia" class="sr-only peer">
                            <div class="p-6 border-2 rounded-xl transition-all duration-300 ease-in-out"
                                 :class="formData.faz_parte_mais_engenharia === 'true' ? 'border-green-500 bg-green-50 shadow-lg' : 'border-gray-300 hover:border-green-400 hover:shadow-md'">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300"
                                             :class="formData.faz_parte_mais_engenharia === 'true' ? 'bg-green-500' : 'bg-green-100'">
                                            <svg class="w-6 h-6 transition-all duration-300" 
                                                 :class="formData.faz_parte_mais_engenharia === 'true' ? 'text-white' : 'text-green-600'"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <span class="text-xl font-bold text-gray-900">Sim</span>
                                    </div>
                                    <!-- Indicador Radio Button -->
                                    <div class="relative w-6 h-6">
                                        <div class="absolute inset-0 rounded-full border-2 transition-all duration-300"
                                             :class="formData.faz_parte_mais_engenharia === 'true' ? 'border-green-500 bg-green-500' : 'border-gray-400 bg-white'">
                                        </div>
                                        <!-- Checkmark interno -->
                                        <svg class="absolute inset-0 w-full h-full text-white transition-all duration-300"
                                             :class="formData.faz_parte_mais_engenharia === 'true' ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"
                                             fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-3 text-sm transition-all duration-300"
                                   :class="formData.faz_parte_mais_engenharia === 'true' ? 'text-gray-700 font-medium' : 'text-gray-600'">
                                    Terei acesso completo à plataforma e poderei realizar o diagnóstico
                                </p>
                            </div>
                        </label>

                        <!-- Opção NÃO -->
                        <label class="cursor-pointer relative group">
                            <input type="radio" x-model="formData.faz_parte_mais_engenharia" value="false" name="mais_engenharia" class="sr-only peer">
                            <div class="p-6 border-2 rounded-xl transition-all duration-300 ease-in-out"
                                 :class="formData.faz_parte_mais_engenharia === 'false' ? 'border-blue-500 bg-blue-50 shadow-lg' : 'border-gray-300 hover:border-blue-400 hover:shadow-md'">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300"
                                             :class="formData.faz_parte_mais_engenharia === 'false' ? 'bg-blue-500' : 'bg-blue-100'">
                                            <svg class="w-6 h-6 transition-all duration-300"
                                                 :class="formData.faz_parte_mais_engenharia === 'false' ? 'text-white' : 'text-blue-600'"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xl font-bold text-gray-900">Não</span>
                                    </div>
                                    <!-- Indicador Radio Button -->
                                    <div class="relative w-6 h-6">
                                        <div class="absolute inset-0 rounded-full border-2 transition-all duration-300"
                                             :class="formData.faz_parte_mais_engenharia === 'false' ? 'border-blue-500 bg-blue-500' : 'border-gray-400 bg-white'">
                                        </div>
                                        <!-- Checkmark interno -->
                                        <svg class="absolute inset-0 w-full h-full text-white transition-all duration-300"
                                             :class="formData.faz_parte_mais_engenharia === 'false' ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"
                                             fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-3 text-sm transition-all duration-300"
                                   :class="formData.faz_parte_mais_engenharia === 'false' ? 'text-gray-700 font-medium' : 'text-gray-600'">
                                    Minha manifestação será registrada para futuras oportunidades
                                </p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Navegação -->
            <div class="flex justify-between mt-12 pt-8 border-t border-gray-200">
                <button type="button" @click="previousStep" 
                        x-show="currentStep > 1"
                        :disabled="isSubmitting"
                        class="px-8 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">
                    ← Voltar
                </button>

                <button type="button" @click="nextStep" 
                        x-show="!isLastStep()"
                        :disabled="isSubmitting"
                        class="ml-auto px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold shadow-lg">
                    Próximo →
                </button>

                <button type="button" @click="submitForm"
                        x-show="isLastStep()"
                        :disabled="isSubmitting"
                        :class="isSubmitting ? 'cursor-wait bg-slate-700 hover:bg-slate-700' : 'bg-green-600 hover:bg-green-700'"
                        class="ml-auto inline-flex items-center gap-3 px-8 py-3 text-white rounded-lg transition font-semibold shadow-lg disabled:opacity-100">
                    <svg x-show="isSubmitting" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Processando envio...' : 'Enviar Manifestação'"></span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function manifestacaoForm() {
            return {
                currentStep: 1,
                newSetor: '',
                isSubmitting: false,
                waitElapsedSeconds: 0,
                waitTimer: null,
                waitLabel: 'Enviando manifestação',
                waitTitle: 'Estamos preparando sua confirmação.',
                waitDescription: 'Validando os dados informados para concluir o registro com segurança.',
                steps: [
                    { title: 'Município' },
                    { title: 'Responsável' },
                    { title: 'Prefeito' },
                    { title: 'Mais Engenharia' }
                ],
                formData: {
                    municipio_nome: '',
                    habitantes_num: null,
                    regional_creapr: '',
                    setores_economicos: [],
                    secretaria_inovacao: '',
                    secretaria_tecnologia_smart: '',
                    secretaria_engenharia: '',
                    faz_parte_mais_engenharia: '',
                    responsavel_nome: '',
                    responsavel_cpf: '',
                    responsavel_telefone: '',
                    responsavel_email: '',
                    responsavel_orgao: '',
                    responsavel_funcao: '',
                    orgao_endereco: '',
                    prefeito_nome: '',
                    prefeito_cpf: '',
                    prefeito_telefone: '',
                    prefeito_mandato: ''
                },
                waitMessages: {
                    standard: [
                        {
                            label: 'Enviando manifestação',
                            title: 'Estamos registrando sua manifestação.',
                            description: 'Validando os dados do município e organizando o protocolo de inscrição.'
                        },
                        {
                            label: 'Quase lá',
                            title: 'Estamos consolidando as informações.',
                            description: 'Fazendo as últimas verificações antes de liberar sua confirmação.'
                        },
                        {
                            label: 'Finalizando',
                            title: 'Seu protocolo está sendo preparado.',
                            description: 'A próxima tela será aberta automaticamente assim que tudo estiver concluído.'
                        }
                    ],
                    maisEngenharia: [
                        {
                            label: 'Enviando manifestação',
                            title: 'Estamos criando o acesso do município.',
                            description: 'Registrando a manifestação e preparando o ambiente inicial da plataforma Smart Crea Cities.'
                        },
                        {
                            label: 'Preparando credenciais',
                            title: 'Estamos organizando o primeiro acesso.',
                            description: 'Validando o responsável, vinculando o município e montando as credenciais temporárias.'
                        },
                        {
                            label: 'Quase pronto',
                            title: 'Estamos concluindo a liberação inicial.',
                            description: 'Esse fluxo leva alguns segundos a mais porque inclui criação de acesso e confirmação por e-mail.'
                        }
                    ]
                },
                waitChecklists: {
                    standard: [
                        { title: 'Recebimento', description: 'Os dados enviados estão sendo conferidos.' },
                        { title: 'Protocolo', description: 'Gerando o identificador único da manifestação.' },
                        { title: 'Confirmação', description: 'Preparando a tela final com o resumo do envio.' }
                    ],
                    maisEngenharia: [
                        { title: 'Manifestação', description: 'Registrando os dados básicos do município.' },
                        { title: 'Acesso', description: 'Criando ou vinculando o usuário responsável.' },
                        { title: 'Credenciais', description: 'Preparando a confirmação e o primeiro acesso.' }
                    ]
                },
                
                addSetor() {
                    if (this.newSetor.trim()) {
                        this.formData.setores_economicos.push(this.newSetor.trim());
                        this.newSetor = '';
                    }
                },
                
                removeSetor(index) {
                    this.formData.setores_economicos.splice(index, 1);
                },
                
                nextStep() {
                    // Validação de cada step antes de avançar
                    if (this.currentStep === 1) {
                        // Validar Step 1: Município
                        if (!this.formData.municipio_nome || !this.formData.municipio_nome.trim()) {
                            alert('Por favor, informe o nome do município.');
                            return;
                        }
                        if (!this.formData.habitantes_num || this.formData.habitantes_num < 1) {
                            alert('Por favor, informe o número de habitantes.');
                            return;
                        }
                        if (!this.formData.regional_creapr) {
                            alert('Por favor, selecione a regional do CREA-PR.');
                            return;
                        }
                        if (!this.formData.setores_economicos || this.formData.setores_economicos.length === 0) {
                            alert('Por favor, adicione pelo menos um setor econômico.');
                            return;
                        }
                    } else if (this.currentStep === 2) {
                        // Validar Step 2: Responsável
                        if (!this.formData.responsavel_nome || !this.formData.responsavel_nome.trim()) {
                            alert('Por favor, informe o nome completo do responsável.');
                            return;
                        }
                        if (!this.formData.responsavel_cpf || !this.formData.responsavel_cpf.trim()) {
                            alert('Por favor, informe o CPF do responsável.');
                            return;
                        }
                        if (!this.formData.responsavel_telefone || !this.formData.responsavel_telefone.trim()) {
                            alert('Por favor, informe o telefone do responsável.');
                            return;
                        }
                        if (!this.formData.responsavel_email || !this.formData.responsavel_email.trim()) {
                            alert('Por favor, informe o e-mail do responsável.');
                            return;
                        }
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(this.formData.responsavel_email)) {
                            alert('Por favor, informe um e-mail válido.');
                            return;
                        }
                        if (!this.formData.responsavel_orgao || !this.formData.responsavel_orgao.trim()) {
                            alert('Por favor, informe o órgão do responsável.');
                            return;
                        }
                        if (!this.formData.responsavel_funcao || !this.formData.responsavel_funcao.trim()) {
                            alert('Por favor, informe a função/cargo do responsável.');
                            return;
                        }
                    } else if (this.currentStep === 3) {
                        // Validar Step 3: Prefeito
                        if (!this.formData.prefeito_nome || !this.formData.prefeito_nome.trim()) {
                            alert('Por favor, informe o nome completo do prefeito.');
                            return;
                        }
                        if (!this.formData.prefeito_cpf || !this.formData.prefeito_cpf.trim()) {
                            alert('Por favor, informe o CPF do prefeito.');
                            return;
                        }
                        if (!this.formData.prefeito_telefone || !this.formData.prefeito_telefone.trim()) {
                            alert('Por favor, informe o telefone do prefeito.');
                            return;
                        }
                        if (!this.formData.prefeito_mandato || !this.formData.prefeito_mandato.trim()) {
                            alert('Por favor, informe o período de mandato.');
                            return;
                        }
                    }
                    
                    // Avança para próximo step se validação passou
                    if (this.currentStep < 4) {
                        this.currentStep++;
                        // Scroll suave para o topo
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                
                previousStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                        // Scroll suave para o topo
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                
                isLastStep() {
                    return this.currentStep === 4;
                },

                isMaisEngenharia() {
                    return this.formData.faz_parte_mais_engenharia === 'true';
                },

                currentWaitMessageIndex() {
                    return Math.min(Math.floor(this.waitElapsedSeconds / 3), this.currentWaitMessages().length - 1);
                },

                currentWaitMessages() {
                    return this.isMaisEngenharia() ? this.waitMessages.maisEngenharia : this.waitMessages.standard;
                },

                get waitChecklist() {
                    return this.isMaisEngenharia() ? this.waitChecklists.maisEngenharia : this.waitChecklists.standard;
                },

                syncWaitContent() {
                    const message = this.currentWaitMessages()[this.currentWaitMessageIndex()];
                    this.waitLabel = message.label;
                    this.waitTitle = message.title;
                    this.waitDescription = message.description;
                },

                waitStepState(index) {
                    const activeIndex = Math.min(Math.floor(this.waitElapsedSeconds / 2), this.waitChecklist.length - 1);

                    if (index < activeIndex) {
                        return 'done';
                    }

                    if (index === activeIndex) {
                        return 'active';
                    }

                    return 'idle';
                },

                waitStepClass(index) {
                    const state = this.waitStepState(index);

                    if (state === 'done') {
                        return 'border-emerald-400/35 bg-emerald-400/12';
                    }

                    if (state === 'active') {
                        return 'border-blue-300/40 bg-blue-400/10 shadow-[0_0_0_1px_rgba(147,197,253,0.08)]';
                    }

                    return 'border-white/10 bg-white/[0.03]';
                },

                waitBadgeClass(index) {
                    const state = this.waitStepState(index);

                    if (state === 'done') {
                        return 'border-emerald-300/50 bg-emerald-400 text-slate-950';
                    }

                    if (state === 'active') {
                        return 'border-blue-200/50 bg-blue-300/20 text-blue-100';
                    }

                    return 'border-white/15 bg-transparent text-slate-300';
                },

                waitDurationText() {
                    if (this.waitElapsedSeconds < 1) {
                        return 'Iniciando agora';
                    }

                    if (this.waitElapsedSeconds === 1) {
                        return '1 segundo';
                    }

                    return `${this.waitElapsedSeconds} segundos`;
                },

                startSubmittingState() {
                    this.stopSubmittingState();
                    this.isSubmitting = true;
                    this.waitElapsedSeconds = 0;
                    this.syncWaitContent();
                    this.waitTimer = setInterval(() => {
                        this.waitElapsedSeconds += 1;
                        this.syncWaitContent();
                    }, 1000);
                },

                stopSubmittingState() {
                    this.isSubmitting = false;
                    if (this.waitTimer) {
                        clearInterval(this.waitTimer);
                        this.waitTimer = null;
                    }
                },
                
                submitForm() {
                    if (this.isSubmitting) {
                        return;
                    }

                    // Validação completa de todos os campos obrigatórios
                    
                    // Step 1: Município
                    if (!this.formData.municipio_nome || !this.formData.municipio_nome.trim()) {
                        alert('Por favor, informe o nome do município.');
                        this.currentStep = 1;
                        return;
                    }
                    if (!this.formData.habitantes_num || this.formData.habitantes_num < 1) {
                        alert('Por favor, informe o número de habitantes.');
                        this.currentStep = 1;
                        return;
                    }
                    if (!this.formData.regional_creapr) {
                        alert('Por favor, selecione a regional do CREA-PR.');
                        this.currentStep = 1;
                        return;
                    }
                    if (!this.formData.setores_economicos || this.formData.setores_economicos.length === 0) {
                        alert('Por favor, adicione pelo menos um setor econômico.');
                        this.currentStep = 1;
                        return;
                    }
                    
                    // Step 2: Responsável
                    if (!this.formData.responsavel_nome || !this.formData.responsavel_nome.trim()) {
                        alert('Por favor, informe o nome completo do responsável.');
                        this.currentStep = 2;
                        return;
                    }
                    if (!this.formData.responsavel_cpf || !this.formData.responsavel_cpf.trim()) {
                        alert('Por favor, informe o CPF do responsável.');
                        this.currentStep = 2;
                        return;
                    }
                    if (!this.formData.responsavel_telefone || !this.formData.responsavel_telefone.trim()) {
                        alert('Por favor, informe o telefone do responsável.');
                        this.currentStep = 2;
                        return;
                    }
                    if (!this.formData.responsavel_email || !this.formData.responsavel_email.trim()) {
                        alert('Por favor, informe o e-mail do responsável.');
                        this.currentStep = 2;
                        return;
                    }
                    // Validação básica de email
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(this.formData.responsavel_email)) {
                        alert('Por favor, informe um e-mail válido.');
                        this.currentStep = 2;
                        return;
                    }
                    if (!this.formData.responsavel_orgao || !this.formData.responsavel_orgao.trim()) {
                        alert('Por favor, informe o órgão do responsável.');
                        this.currentStep = 2;
                        return;
                    }
                    if (!this.formData.responsavel_funcao || !this.formData.responsavel_funcao.trim()) {
                        alert('Por favor, informe a função/cargo do responsável.');
                        this.currentStep = 2;
                        return;
                    }
                    
                    // Step 3: Prefeito
                    if (!this.formData.prefeito_nome || !this.formData.prefeito_nome.trim()) {
                        alert('Por favor, informe o nome completo do prefeito.');
                        this.currentStep = 3;
                        return;
                    }
                    if (!this.formData.prefeito_cpf || !this.formData.prefeito_cpf.trim()) {
                        alert('Por favor, informe o CPF do prefeito.');
                        this.currentStep = 3;
                        return;
                    }
                    if (!this.formData.prefeito_telefone || !this.formData.prefeito_telefone.trim()) {
                        alert('Por favor, informe o telefone do prefeito.');
                        this.currentStep = 3;
                        return;
                    }
                    if (!this.formData.prefeito_mandato || !this.formData.prefeito_mandato.trim()) {
                        alert('Por favor, informe o período de mandato.');
                        this.currentStep = 3;
                        return;
                    }
                    
                    // Step 4: Mais Engenharia
                    if (!this.formData.faz_parte_mais_engenharia) {
                        alert('Por favor, informe se o município faz parte do programa "Mais Engenharia".');
                        this.currentStep = 4;
                        return;
                    }

                    this.startSubmittingState();
                    
                    // Enviar dados via AJAX
                    fetch('{{ route("manifestacao.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.formData)
                    })
                    .then(response => {
                        if (!response.ok) {
                            // Se não é 2xx, tentar ler a resposta como JSON para ver erros de validação
                            return response.json().then(err => {
                                let errorMsg = err.message || 'Erro ao processar formulário';
                                if (err.errors) {
                                    // Pega a primeira mensagem de erro
                                    const firstKey = Object.keys(err.errors)[0];
                                    if (firstKey && err.errors[firstKey].length > 0) {
                                        errorMsg = err.errors[firstKey][0];
                                    }
                                }
                                throw new Error(errorMsg);
                            }).catch(err => {
                                if (err instanceof SyntaxError) {
                                    throw new Error('Erro de comunicação com o servidor. Por favor, tente novamente.');
                                }
                                throw err;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            this.stopSubmittingState();
                            alert(data.message || 'Erro ao processar formulário.');
                        }
                    })
                    .catch(error => {
                        this.stopSubmittingState();
                        console.error('Erro:', error);
                        alert(error.message || 'Erro ao enviar manifestação. Por favor, tente novamente.');
                    });
                }
            };
        }
    </script>
</body>
</html>

