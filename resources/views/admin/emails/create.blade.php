<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comunicados & E-mails | Smart Crea Cities</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('partials.favicons')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-22 md:h-26">
                <div class="flex items-center space-x-4 py-4">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('assets/img/card-smart-crea-cities-negativo.png') }}" alt="Smart Crea Cities" class="h-16 sm:h-20 w-auto object-contain">
                    </a>
                    <div class="border-l border-gray-300 h-10"></div>
                    <div>
                        <p class="text-sm text-gray-600">Painel</p>
                        <p class="font-bold text-blue-900">Administrativo CREA-PR</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Dashboard
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.submissoes.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Submissões
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.emails.create') }}" class="text-blue-900 hover:text-blue-950 font-bold text-sm bg-blue-50 px-3 py-1.5 rounded-lg">
                        Enviar E-mail
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Administradores
                    </a>
                    <div class="text-right hidden md:block">
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
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Comunicação e E-mails</h1>
            <p class="text-gray-600">Componha comunicados de e-mail personalizados ou credenciais para os municípios ativos no programa.</p>
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

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">Por favor, corrija os seguintes erros:</h3>
                        <ul class="mt-1 list-disc list-inside text-xs text-red-700 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Coluna do Formulário (Composer) -->
            <div class="lg:col-span-7 bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Compor Comunicado
                </h2>

                <form id="emailForm" method="POST" action="{{ route('admin.emails.send') }}">
                    @csrf
                                   <!-- Seleção de Destinatários -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Destinatários</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 mb-4">
                            <label class="flex flex-col p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="recipient_type" value="all" checked class="sr-only peer">
                                <span class="peer-checked:text-blue-600 peer-checked:font-bold text-gray-700 text-sm">Todos</span>
                                <span class="text-xs text-gray-400 mt-1">Todos ativos</span>
                            </label>
                            <label class="flex flex-col p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="recipient_type" value="approved" class="sr-only peer">
                                <span class="peer-checked:text-blue-600 peer-checked:font-bold text-gray-700 text-sm">Aprovados</span>
                                <span class="text-xs text-gray-400 mt-1">Fase diagnóstica</span>
                            </label>
                            <label class="flex flex-col p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="recipient_type" value="pending" class="sr-only peer">
                                <span class="peer-checked:text-blue-600 peer-checked:font-bold text-gray-700 text-sm">Pendentes</span>
                                <span class="text-xs text-gray-400 mt-1">Em avaliação</span>
                            </label>
                            <label class="flex flex-col p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="recipient_type" value="selected" class="sr-only peer" id="typeSelectedBtn">
                                <span class="peer-checked:text-blue-600 peer-checked:font-bold text-gray-700 text-sm">Selecionar</span>
                                <span class="text-xs text-gray-400 mt-1">Lista manual</span>
                            </label>
                            <label class="flex flex-col p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="recipient_type" value="custom" class="sr-only peer" id="typeCustomBtn">
                                <span class="peer-checked:text-blue-600 peer-checked:font-bold text-gray-700 text-sm">E-mail Direto</span>
                                <span class="text-xs text-gray-400 mt-1">Digitar e-mail(s)</span>
                            </label>
                        </div>

                        <!-- Lista de Seleção de Municípios Manual -->
                        <div id="manualSelectionWrapper" class="hidden border rounded-xl p-4 bg-gray-50 max-h-60 overflow-y-auto mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-bold text-gray-500 uppercase">Selecione os Municípios</p>
                                <div class="flex gap-2">
                                    <button type="button" id="selectAllBtn" class="text-xs text-blue-600 hover:underline">Marcar Todos</button>
                                    <span class="text-gray-300 text-xs">|</span>
                                    <button type="button" id="deselectAllBtn" class="text-xs text-blue-600 hover:underline">Desmarcar</button>
                                </div>
                            </div>
                            <input type="text" id="searchMunicipio" placeholder="🔍 Buscar município..." class="w-full text-sm border-gray-300 rounded-lg p-2 mb-3 focus:ring-blue-500 focus:border-blue-500">
                            
                            <div class="space-y-2" id="municipiosListContainer">
                                @foreach($municipios as $m)
                                    <label class="flex items-center space-x-3 p-1.5 hover:bg-gray-100 rounded transition-colors text-sm text-gray-700 cursor-pointer municipio-item" data-name="{{ $m->municipio_nome }}">
                                        <input type="checkbox" name="selected_municipios[]" value="{{ $m->id }}" class="rounded text-blue-600 focus:ring-blue-500 municipio-checkbox">
                                        <span>{{ $m->municipio_nome }} <span class="text-xs text-gray-400">({{ $m->status === 'approved' ? 'Aprovado' : 'Pendente' }})</span></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Input de E-mail Personalizado -->
                        <div id="customEmailWrapper" class="hidden border rounded-xl p-4 bg-gray-50 mb-4">
                            <label for="custom_email" class="block text-sm font-bold text-gray-700 mb-2">E-mail(s) do Destinatário</label>
                            <input type="text" id="custom_email" name="custom_email" placeholder="exemplo@email.com ou separe múltiplos com vírgula..." class="w-full text-sm border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1.5">Insira um ou mais e-mails válidos. Caso o e-mail pertença a um município ativo cadastrado, as variáveis dinâmicas serão preenchidas automaticamente!</p>
                        </div>
                    </div>

                    <!-- Assunto -->
                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-bold text-gray-700 mb-2">Assunto do E-mail</label>
                        <input type="text" id="subject" name="subject" required placeholder="Digite o assunto..." class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Barra de Tags Dinâmicas -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Variáveis Dinâmicas (Clique para Inserir)</label>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="insertTag('{municipio_nome}')" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 transition-colors shadow-sm">
                                {municipio_nome}
                            </button>
                            <button type="button" onclick="insertTag('{responsavel_nome}')" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 transition-colors shadow-sm">
                                {responsavel_nome}
                            </button>
                            <button type="button" onclick="insertTag('{responsavel_email}')" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 transition-colors shadow-sm">
                                {responsavel_email}
                            </button>
                            <button type="button" onclick="insertTag('{protocolo}')" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200 transition-colors shadow-sm">
                                {protocolo}
                            </button>
                            <button type="button" onclick="insertTag('{senha_municipio}')" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold rounded-lg border border-amber-200 transition-colors shadow-sm flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                {senha_municipio}
                            </button>
                        </div>
                    </div>

                    <!-- Corpo do E-mail -->
                    <div class="mb-6">
                        <label for="body" class="block text-sm font-bold text-gray-700 mb-2">Mensagem (Corpo do E-mail)</label>
                        <textarea id="body" name="body" required rows="10" placeholder="Olá {responsavel_nome}, escreva a mensagem aqui..." class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 font-sans"></textarea>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all text-center flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Disparar Campanha de E-mails
                        </button>
                    </div>
                </form>
            </div>

            <!-- Coluna de Live Preview -->
            <div class="lg:col-span-5 flex flex-col">
                <div class="bg-slate-900 rounded-2xl shadow-xl border border-slate-800 p-6 text-white flex-1 flex flex-col">
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-800">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                            Visualização em Tempo Real
                        </h2>
                        <!-- Responsive Toggle buttons -->
                        <div class="flex bg-slate-800 p-0.5 rounded-lg">
                            <button type="button" id="previewDesktopBtn" class="px-2 py-1 text-xs rounded bg-slate-700 font-bold transition-all flex items-center gap-1">
                                🖥️ Desktop
                            </button>
                            <button type="button" id="previewMobileBtn" class="px-2 py-1 text-xs rounded text-slate-400 hover:text-white transition-all flex items-center gap-1">
                                📱 Mobile
                            </button>
                        </div>
                    </div>

                    <!-- Mockup Container -->
                    <div class="flex-1 flex justify-center items-center bg-slate-950 rounded-xl p-4 overflow-y-auto max-h-[600px] border border-slate-800 transition-all duration-300" id="mockupViewport">
                        <div class="w-full bg-white text-slate-800 rounded-lg p-6 shadow-md transition-all duration-300 mx-auto" id="emailBodyContainer" style="max-width: 600px;">
                            <!-- Header -->
                            <div class="pb-4 border-b-2 border-blue-600 mb-6 text-center">
                                <div class="font-bold text-xl text-blue-700 tracking-tight">Smart Crea Cities</div>
                                <div class="text-[10px] text-slate-500">CREA-PR - Conselho Regional de Engenharia e Agronomia do Paraná</div>
                            </div>

                            <!-- Mockup Subject Info -->
                            <div class="mb-4 p-2 bg-slate-50 border rounded text-xs text-slate-500">
                                <strong>Assunto:</strong> <span id="previewSubject">Seu Assunto Irá Aqui...</span>
                            </div>

                            <!-- Content -->
                            <div class="text-sm leading-relaxed text-slate-800 whitespace-pre-line" id="previewBody">
                                Olá, seu texto com variáveis aparecerá de forma dinâmica aqui à medida que você for escrevendo.
                            </div>

                            <!-- Footer -->
                            <div class="mt-8 pt-4 border-t border-slate-100 text-center text-[10px] text-slate-400">
                                <strong>CREA-PR - Conselho Regional de Engenharia e Agronomia do Paraná</strong>
                                <div>Smart Crea Cities - Trilha Formativa dos 3E's</div>
                                <div class="mt-2">Este é um comunicado oficial enviado pela equipe do CREA-PR.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripting for UI and Live Preview -->
    <script>
        const subjectInput = document.getElementById('subject');
        const bodyInput = document.getElementById('body');
        const previewSubject = document.getElementById('previewSubject');
        const previewBody = document.getElementById('previewBody');
        
        // Recipient Selection Toggling
        const recipientRadios = document.querySelectorAll('input[name="recipient_type"]');
        const manualWrapper = document.getElementById('manualSelectionWrapper');
        const customWrapper = document.getElementById('customEmailWrapper');
        
        recipientRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                if (e.target.value === 'selected') {
                    manualWrapper.classList.remove('hidden');
                    customWrapper.classList.add('hidden');
                } else if (e.target.value === 'custom') {
                    customWrapper.classList.remove('hidden');
                    manualWrapper.classList.add('hidden');
                    document.getElementById('custom_email').focus();
                } else {
                    manualWrapper.classList.add('hidden');
                    customWrapper.classList.add('hidden');
                }
            });
        });

        // Search municipalities
        const searchInput = document.getElementById('searchMunicipio');
        const municipioItems = document.querySelectorAll('.municipio-item');
        
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            municipioItems.forEach(item => {
                const name = item.getAttribute('data-name').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                if (name.includes(query)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });

        // Select All / Deselect All
        document.getElementById('selectAllBtn').addEventListener('click', () => {
            document.querySelectorAll('.municipio-checkbox').forEach(cb => cb.checked = true);
        });
        document.getElementById('deselectAllBtn').addEventListener('click', () => {
            document.querySelectorAll('.municipio-checkbox').forEach(cb => cb.checked = false);
        });

        // Tag Insertion at cursor position
        function insertTag(tag) {
            const textarea = bodyInput;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            textarea.value = text.substring(0, start) + tag + text.substring(end);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + tag.length;
            updatePreview();
        }

        // Dummy tags values for preview resolving
        const dummyValues = {
            '{municipio_nome}': 'Município de Assaí',
            '{responsavel_nome}': 'Dr. Carlos Ferreira',
            '{responsavel_email}': 'carlos.ferreira@assai.pr.gov.br',
            '{protocolo}': 'PR-2026/99A7',
            '{senha_municipio}': 'XyZ#8892_Temp'
        };

        // Render Live Preview with placeholders resolved
        function updatePreview() {
            let subjectText = subjectInput.value || 'Seu Assunto Irá Aqui...';
            let bodyText = bodyInput.value || 'Olá {responsavel_nome},\n\nSua mensagem customizada será gerada em tempo real.';

            // Resolve placeholders for previewing
            for (const [placeholder, value] of Object.entries(dummyValues)) {
                subjectText = subjectText.replaceAll(placeholder, value);
                bodyText = bodyText.replaceAll(placeholder, value);
            }

            previewSubject.textContent = subjectText;
            previewBody.innerHTML = bodyText.replace(/\n/g, '<br>');
        }

        subjectInput.addEventListener('input', updatePreview);
        bodyInput.addEventListener('input', updatePreview);

        // Viewport switching (Desktop/Mobile preview simulation)
        const previewDesktopBtn = document.getElementById('previewDesktopBtn');
        const previewMobileBtn = document.getElementById('previewMobileBtn');
        const emailContainer = document.getElementById('emailBodyContainer');
        const mockupViewport = document.getElementById('mockupViewport');

        previewDesktopBtn.addEventListener('click', () => {
            previewDesktopBtn.className = "px-2 py-1 text-xs rounded bg-slate-700 font-bold transition-all flex items-center gap-1";
            previewMobileBtn.className = "px-2 py-1 text-xs rounded text-slate-400 hover:text-white transition-all flex items-center gap-1";
            emailContainer.style.maxWidth = "600px";
            mockupViewport.style.padding = "1rem";
        });

        previewMobileBtn.addEventListener('click', () => {
            previewMobileBtn.className = "px-2 py-1 text-xs rounded bg-slate-700 font-bold transition-all flex items-center gap-1";
            previewDesktopBtn.className = "px-2 py-1 text-xs rounded text-slate-400 hover:text-white transition-all flex items-center gap-1";
            emailContainer.style.maxWidth = "350px";
            mockupViewport.style.padding = "2rem 0";
        });

        // Initialize preview on page load
        updatePreview();
    </script>
</body>
</html>
