@extends('layouts.admin')

@section('title', 'Editar Administrador - Painel CREA-PR')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <!-- Breadcrumbs e Título -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
            <span>&rarr;</span>
            <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600 transition">Administradores</a>
            <span>&rarr;</span>
            <span class="text-gray-900 font-medium">Editar</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-1">Editar Administrador</h1>
        <p class="text-gray-600">Altere o nome, e-mail ou redefina a senha de acesso da conta administrativa</p>
    </div>

    <!-- Card de Formulário -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-6 sm:p-8">
            
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg">
                    <p class="font-bold text-sm mb-2">Por favor, corrija os seguintes erros:</p>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Campo Nome -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nome Completo</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           placeholder="Digite o nome completo do administrador"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo E-mail -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Endereço de E-mail</label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $user->email) }}" 
                           required 
                           placeholder="exemplo@crea-pr.org.br"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nota informativa sobre alteração de senha opcional -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-blue-800 leading-relaxed font-semibold">
                            Caso não queira alterar a senha atual deste administrador, **deixe os campos de senha abaixo totalmente em branco**. A senha atual continuará ativa e intocada.
                        </p>
                    </div>
                </div>

                <!-- Grid de Senhas (Opcionais na Edição) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Campo Senha -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Nova Senha (Opcional)</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               placeholder="Mínimo de 8 caracteres"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmação de Senha -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirmar Nova Senha</label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               placeholder="Repita a nova senha se preenchida"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                    </div>
                </div>

                <!-- Rodapé do Formulário / Ações -->
                <div class="pt-6 border-t border-gray-150 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}" 
                       class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition duration-150">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-sm shadow-md hover:shadow-lg transition duration-200">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
