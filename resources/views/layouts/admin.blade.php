<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - CREA-PR')</title>
    
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
                        <p class="text-sm text-gray-600">Painel</p>
                        <p class="font-bold text-blue-900">Administrativo CREA-PR</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" 
                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Submissões
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.questions.index') }}" 
                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Questões
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.events.index') }}" 
                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Eventos
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.repository.index') }}" 
                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Repositório
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('admin.users.index') }}" 
                        class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        Administradores
                    </a>
                    
                    @auth
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
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo -->
    <main>
        @yield('content')
    </main>

</body>
</html>

