<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedToRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(Auth::check()) {
            $user = Auth::user();

            return match ($user->role) {
              UserRole::ADMIN => redirect('/admin'),
              UserRole::PARTNER  => redirect('/partner'),
              default => redirect('/dashboard'),
            };
        }

        return $next($request);
    }
}
