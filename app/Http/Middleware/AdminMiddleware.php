<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Cek apakah pengguna sudah login DAN merupakan admin
    if (auth()->check() && auth()->user()->is_admin) {
        return $next($request); // Lanjutkan ke halaman admin
    }

    // Jika tidak, tendang ke halaman login
    return redirect('/login')->with('error', 'Anda tidak memiliki akses admin.');
}
}
