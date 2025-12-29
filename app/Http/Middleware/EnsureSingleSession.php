<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\LoginIp;

class EnsureSingleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Si el usuario no tiene perfil asociado, logout
            if (!$user->usuario) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'Perfil de usuario no encontrado.']);
            }

            // Verificar sesión en base de datos
            $sessionRecord = LoginIp::where('usuario_id', $user->usuario->id)->first();

            // Strict Check: Match Session ID (and implicitly IP/Browser via cookie context)
            if (!$sessionRecord || $sessionRecord->session_token !== Session::getId()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->withErrors(['email' => 'Sesión iniciada en otro dispositivo o navegador.']);
            }
        }

        return $next($request);
    }
}
