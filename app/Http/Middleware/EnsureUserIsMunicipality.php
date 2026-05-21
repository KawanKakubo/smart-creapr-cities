<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsMunicipality
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || $user->role !== 'municipality') {
            return redirect()->route('login')->with('error', 'Acesso negado. Apenas municípios podem acessar esta área.');
        }

        // Verifica se o município está ativo
        $submission = $user->submission;
        if ($submission && !$submission->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Esta conta de município foi inativada pela administração do CREA-PR.');
        }

        return $next($request);
    }
}
