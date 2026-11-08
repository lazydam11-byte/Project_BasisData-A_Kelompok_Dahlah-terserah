<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekJabatan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
       public function handle(Request $request, Closure $next, string ...$jabatan): Response
    {
        $pegawai = Auth::guard('pegawai')->user();

        if (! $pegawai || ! in_array($pegawai->jabatan, $jabatan, true)) {
            abort(403, 'Jabatan Anda tidak memiliki akses ke fitur ini.');
        }

        return $next($request);
    }
}
