<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscrições Encerradas | Smart Crea Cities</title>
    <link href="https://fonts.bunny.net/css?family=inter:300,400,600,700,800,900" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .header-blur {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
    @include('partials.favicons')
</head>
<body class="bg-gradient-to-br from-gray-50 to-slate-200 min-h-screen flex flex-col justify-between">
    <!-- Header -->
    <nav class="fixed top-0 left-0 right-0 z-50 header-blur shadow-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-22 md:h-26 lg:h-30">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center transition-opacity hover:opacity-80">
                    <img src="{{ asset('assets/img/card-smart-crea-cities-negativo.png') }}" 
                         alt="Smart Crea Cities" 
                         class="h-20 sm:h-24 md:h-28 w-auto object-contain">
                </a>
                
                <!-- Botões Desktop -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}" 
                       class="nav-btn px-6 py-2.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition shadow-md">
                        Fazer Login
                    </a>
                    <a href="{{ route('home') }}" 
                       class="nav-btn px-6 py-2.5 border-2 border-gray-400 text-gray-700 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Voltar ao Início
                    </a>
                </div>
                
                <!-- Botões Mobile -->
                <div class="flex md:hidden items-center space-x-2">
                    <a href="{{ route('login') }}" 
                       class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </a>
                    <a href="{{ route('home') }}" 
                       class="p-2 border-2 border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow flex items-center justify-center px-4 pt-28 md:pt-36 lg:pt-40 pb-12">
        <div class="max-w-xl w-full bg-white rounded-2xl shadow-2xl border border-gray-100 p-8 md:p-10 text-center relative overflow-hidden">
            <!-- Decorative Subtle Gradient Top Bar -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500"></div>

            <!-- Lock and Clock Icon Container -->
            <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 relative shadow-inner">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <div class="absolute bottom-1 right-1 bg-white rounded-full p-1.5 shadow-md">
                    <svg class="w-5 h-5 text-red-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Blocked Message Header -->
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-3 tracking-tight">
                Inscrições Encerradas
            </h1>
            
            <p class="text-base text-gray-600 font-medium max-w-md mx-auto mb-8 leading-relaxed">
                O período para inscrições finalizou. Agradecemos imensamente o interesse de todos os municípios na nossa jornada rumo a cidades mais inteligentes.
            </p>

            <!-- Details Card -->
            <div class="bg-slate-50 border border-slate-100 rounded-xl p-5 mb-8 text-left space-y-3">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Informações Importantes</h3>
                <div class="flex items-start text-sm text-slate-655 space-x-3">
                    <svg class="w-5 h-5 text-slate-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-slate-600">Para os municípios que já realizaram a inscrição, o acesso aos diagnósticos e painéis segue ativo normalmente através da área de login.</p>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('login') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg font-bold text-sm shadow-lg hover:bg-blue-700 transition">
                    Acessar Área do Município
                </a>
                <a href="{{ route('home') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-bold text-sm hover:bg-gray-50 transition">
                    Voltar ao Portal
                </a>
            </div>
        </div>
    </div>

    <!-- Footer / Contact Info -->
    <div class="py-6 text-center border-t border-gray-200 bg-white">
        <p class="text-gray-500 text-sm">
            Dúvidas ou suporte? Fale com a equipe do CREA-PR: 
            <a href="mailto:cidadesinteligentes@crea-pr.org.br" class="text-blue-600 font-semibold hover:underline">
                cidadesinteligentes@crea-pr.org.br
            </a>
        </p>
    </div>
</body>
</html>
