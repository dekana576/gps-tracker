<?php

// app/Http/Middleware/EnsureUserIsAdmin.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika pengguna bukan admin, blokir akses ke halaman admin
        if (Auth::check() && !Auth::user()->is_admin) {
            return redirect()->route('user')->with('error', 'Anda tidak diizinkan mengakses halaman admin.');
        }

        return $next($request);
    }
}
