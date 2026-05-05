<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = strtolower(trim(Auth::user()->role));

        // FIX: kalau roles ke-detect sebagai 1 string "admin,kasir"
        if (count($roles) === 1 && str_contains($roles[0], ',')) {
            $roles = explode(',', $roles[0]);
        }

        $allowedRoles = array_map(fn($r) => strtolower(trim($r)), $roles);

        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
