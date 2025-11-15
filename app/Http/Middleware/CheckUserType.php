<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userTypeId = $user->type_user_id;
        $prefix = $request->route()->getPrefix();
        $cleanPrefix = ltrim($prefix, '/');

        // Admin pode acessar qualquer prefixo
        if ($userTypeId == 1) {
            return $next($request);
        }

        // Cliente só pode acessar rotas de client
        if ($userTypeId == 2) {
            if ($cleanPrefix === 'client') {
                return $next($request);
            }
            return redirect()->route('client.dashboardClient');
        }

        // Outros tipos de usuário (caso existam)
        abort(403, 'Unauthorized');
    }
}
