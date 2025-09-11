<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // Ambil user yang sedang login

        // Ambil role yang diteruskan ke middleware
        $roles = func_get_args();
        array_shift($roles); // hapus $request
        array_shift($roles); // hapus $next, tersisa role-role yang diberikan di route

        // Jika user tidak login atau role tidak sesuai, batalkan akses
        if (!$user || !in_array($user->role, $roles)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
