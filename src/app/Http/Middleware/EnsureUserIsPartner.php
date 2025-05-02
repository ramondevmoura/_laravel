<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPartner
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        if (!$user || $user->role->value !== $role) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }

}
