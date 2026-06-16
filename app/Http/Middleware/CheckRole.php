<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Não autenticado');
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Acesso negado');
        }

        return $next($request);
    }
}
