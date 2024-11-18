<?php

// app/Http/Middleware/EnsureUserIsNotAdmin.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsNotAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika pengguna adalah admin, blokir akses ke halaman user
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin')->with('error', 'Admin tidak diizinkan mengakses halaman ini.');
        }

        return $next($request);
    }
}

