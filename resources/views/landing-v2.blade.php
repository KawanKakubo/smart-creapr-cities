<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart CREAPR Cities 2026 | CREA-PR</title>
    <meta name="description" content="Programa Smart CREAPR Cities 2026 - Transformando municípios paranaenses em Territórios Inteligentes">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .hero-container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Vídeo apenas em desktop */
        .video-background {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            z-index: 0;
            display: none;
        }

        @media (min-width: 768px) {
            .video-background {
                display: block;
            }
            .image-background {
                display: none;
            }
        }

        /* Imagem para mobile */
        .image-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        /* Overlay escuro com toque de cor — vídeo vira apenas atmosfera */
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                160deg,
                rgba(15, 10, 40, 0.92) 0%,
                rgba(55, 30, 140, 0.88) 50%,
                rgba(10, 60, 30, 0.90) 100%
            );
            backdrop-filter: blur(5px);
            z-index: 1;
        }

        .content-overlay {
            position: relative;
            z-index: 2;
        }

        /* Tipografia */
        .hero-title {
            letter-spacing: -0.02em;
            line-height: 1.1;
            text-shadow: 0 4px 24px rgba(0, 0, 0, 0.5);
        }

        .hero-subtitle {
            letter-spacing: 0.01em;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.4);
        }

        .hero-highlight {
            letter-spacing: -0.01em;
            color: #ffffff;
            text-shadow: 0 0 40px rgba(32, 225, 93, 0.35), 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        /* Botões */
        .btn-primary {
            background: linear-gradient(135deg, #20e15d 0%, #1bc950 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 30px rgba(32, 225, 93, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(32, 225, 93, 0.4);
            filter: brightness(1.05);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        /* Header verde com acabamento profissional */
        .header-green-pro {
            background: linear-gradient(180deg, #20e15d 0%, #1ed65a 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 8px 24px rgba(32, 225, 93, 0.22);
        }

        /* Nav link style */
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
            background: #6c3bf4;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-btn:hover::after {
            width: 100%;
        }

        /* Animações */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }

    </style>
    @include('partials.favicons')
</head>
<body class="antialiased overflow-x-hidden">
    <!-- Header verde -->
    <nav class="fixed top-0 w-full z-50 header-green-pro">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-22 md:h-26 lg:h-30">
                <!-- Logo oficial -->
                <div class="flex items-center gap-3 md:gap-4">
                    <img src="{{ asset('assets/img/smart-crea-cities.png') }}"
                         alt="Smart CREAPR Cities"
                         class="h-20 sm:h-24 md:h-28 w-auto object-contain">
                </div>

                <!-- Botões Desktop -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ asset('assets/pdfs/Smart_Crea_Cities_2026_Regulamento_Termo_Manual_COMPLETO.pdf') }}"
                       target="_blank"
                              class="nav-btn text-slate-900 px-2 py-3 font-semibold text-sm hover:text-slate-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Regulamento
                    </a>
                    <a href="{{ asset('assets/docs/edital-smart-crea-cities-2026.pdf') }}"
                       target="_blank"
                              class="nav-btn text-slate-900 px-2 py-3 font-semibold text-sm hover:text-slate-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Edital
                    </a>
                    <a href="{{ route('login') }}"
                       class="nav-btn text-slate-900 px-2 py-3 font-semibold text-sm hover:text-slate-700">
                        Login
                    </a>
                    <a href="{{ route('manifestacao.show') }}"
                       class="bg-slate-900 text-white px-6 py-3 rounded-lg font-bold text-sm hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl whitespace-nowrap">
                        Manifestar Interesse
                    </a>
                </div>

                <!-- Botões Mobile Compactos -->
                <div class="flex md:hidden items-center gap-1.5">
                    <a href="{{ asset('assets/pdfs/Smart_Crea_Cities_2026_Regulamento_Termo_Manual_COMPLETO.pdf') }}"
                       target="_blank"
                              class="text-slate-900 px-2.5 py-2.5 rounded-lg font-semibold text-xs hover:text-slate-700 transition-all flex items-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </a>
                    <a href="{{ asset('assets/docs/edital-smart-crea-cities-2026.pdf') }}"
                       target="_blank"
                              class="text-slate-900 px-2.5 py-2.5 rounded-lg font-semibold text-xs hover:text-slate-700 transition-all flex items-center"
                              aria-label="Edital">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}"
                       class="text-slate-900 px-3 py-2.5 rounded-lg font-semibold text-xs hover:text-slate-700 transition-all">
                        Login
                    </a>
                    <a href="{{ route('manifestacao.show') }}"
                       class="bg-slate-900 text-white px-3 py-2.5 rounded-lg font-semibold text-xs whitespace-nowrap">
                        Manifestar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-container">
        <!-- Vídeo para Desktop -->
        <video autoplay muted loop playsinline class="video-background">
            <source src="{{ asset('assets/videos/cidade-smart.mp4') }}" type="video/mp4">
        </video>

        <!-- Imagem para Mobile -->
        <img src="{{ asset('assets/img/city-background.jpg') }}"
             alt="Cidade Inteligente"
             class="image-background">

        <!-- Overlay com gradiente -->
        <div class="hero-overlay"></div>

        <!-- Conteúdo Central -->
        <div class="content-overlay min-h-screen flex items-center justify-center px-4 pt-28 md:pt-32 lg:pt-36 pb-16 md:pb-20">
            <div class="text-center max-w-5xl mx-auto">

                <!-- Título Principal -->
                <h1 class="hero-title text-3xl sm:text-5xl md:text-6xl lg:text-7xl text-white mb-3 md:mb-5 animate-fade-in-up">
                    <span class="font-semibold">Smart</span> <span class="font-extrabold">CREA-PR</span> <span class="font-semibold">Cities</span>
                </h1>

                <!-- Subtítulo -->
                <p class="hero-subtitle text-base sm:text-xl md:text-2xl lg:text-3xl text-white/90 mb-2 font-light animate-fade-in-up delay-100">
                    Transformando Municípios em
                </p>

                <!-- Destaque -->
                <p class="hero-highlight text-xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-8 md:mb-10 lg:mb-14 animate-fade-in-up delay-200">
                    Territórios Inteligentes
                </p>

                <!-- CTAs -->
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center items-center mb-8 md:mb-10 animate-fade-in-up delay-300">
                    <a href="{{ route('manifestacao.show') }}"
                       class="btn-primary w-full sm:w-auto px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-sm md:text-base text-slate-900 inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Manifestar Interesse
                    </a>

                    <a href="{{ asset('assets/pdfs/Smart_Crea_Cities_2026_Regulamento_Termo_Manual_COMPLETO.pdf') }}"
                       target="_blank"
                       class="btn-secondary w-full sm:w-auto px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-sm md:text-base text-white inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Regulamento
                    </a>

                    <a href="{{ asset('assets/docs/edital-smart-crea-cities-2026.pdf') }}"
                       target="_blank"
                       class="btn-secondary w-full sm:w-auto px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-sm md:text-base text-white inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Edital
                    </a>

                    <a href="{{ route('login') }}"
                       class="btn-secondary w-full sm:w-auto px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-sm md:text-base text-white inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Acessar Plataforma
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Realização e Apoio -->
    <section class="bg-slate-950 py-12 md:py-20">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <div class="flex flex-col md:flex-row items-center justify-center gap-12 md:gap-24">
                <!-- CREA-PR -->
                <div class="flex flex-col items-center flex-1">
                    <p class="text-gray-400 text-xs md:text-sm uppercase mb-6 md:mb-8" style="letter-spacing: 0.25em; opacity: 0.7;">
                        Realização
                    </p>
                    <div class="h-20 flex items-center justify-center">
                        <img src="{{ asset('assets/img/logo-crea-pr-preto.png') }}"
                             alt="CREA-PR"
                             class="h-full w-auto object-contain">
                    </div>
                </div>

                <!-- Separador -->
                <div class="hidden md:block w-px h-32 bg-white/10"></div>
                <div class="block md:hidden w-32 h-px bg-white/10"></div>

                <!-- Assaí -->
                <div class="flex flex-col items-center flex-1">
                    <p class="text-gray-400 text-xs md:text-sm uppercase mb-6 md:mb-8" style="letter-spacing: 0.25em; opacity: 0.7;">
                        Apoio e Metodologia
                    </p>
                    <div class="h-20 flex items-center justify-center gap-4">
                        <span class="text-gray-500 text-sm md:text-md italic font-medium">Powered by</span>
                        <img src="{{ asset('assets/img/logomarca-negativo.png') }}"
                             alt="Município de Assaí"
                             class="h-full w-auto object-contain">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 text-white py-5 md:py-6 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4">
            <p class="text-gray-500 text-xs md:text-sm text-center">
                &copy; 2026 CREA-PR — Smart CREAPR Cities. Todos os direitos reservados.
            </p>
        </div>
    </footer>
</body>
</html>
