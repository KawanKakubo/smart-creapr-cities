<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Força HTTPS fora dos ambientes de desenvolvimento e dos hosts locais.
        // Necessário para funcionar corretamente atrás do WAF do CREA-PR,
        // sem fazer o servidor HTTP do artisan servir links HTTPS inválidos.
        $host = strtolower(request()->getHost());
        $isLocalHost = in_array($host, ['localhost', '127.0.0.1', '::1'], true);

        if (! app()->environment(['local', 'testing']) && ! $isLocalHost) {
            URL::forceScheme('https');
        }
    }
}
