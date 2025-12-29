<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Usuario;
use App\Models\Correo;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validar datos
        $request->validate([
            'nickname' => 'required',
            'email' => 'required|email|unique:cuentas,email|regex:/^[a-zA-Z0-9._%+-]+@soy\.sena\.edu\.co$/i',
            'password' => 'required|confirmed|min:6'
        ], [
            'email.unique' => 'Este correo ya está registrado',
            'email.regex' => 'Debes usar un correo institucional @soy.sena.edu.co',
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear Cuenta
            $cuenta = \App\Models\Cuenta::create([
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            // 2. Crear Usuario
            $usuario = \App\Models\Usuario::create([
                'cuenta_id' => $cuenta->id,
                'nickname' => $request->input('nickname'),
                'rol_id' => 3, // Prosumer
                'estado_id' => 1, // Activo
                'imagen' => 'default.png',
                'descripcion' => 'Nuevo en Tu Mercado SENA',
                'link' => null
            ]);

            DB::commit();

            // 3. NO iniciar sesión automáticamente. Redirigir a Login.
            return redirect()->route('login')->with('success', '¡Registro exitoso! Por favor inicia sesión.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['email' => 'Error al registrar: ' . $e->getMessage()])->withInput();
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $cuenta = Auth::user();
            $usuario = $cuenta->usuario;

            if (!$usuario) {
                Auth::logout();
                return back()->withErrors(['email' => 'Usuario no asociado a esta cuenta.']);
            }

            // ==========================================
            // SINGLE SESSION (Legacy Table: login_ip)
            // ==========================================
            
            // 1. Cerrar sesiones anteriores (Eliminar registros previos)
            \App\Models\LoginIp::where('usuario_id', $usuario->id)->delete();

            // 2. Registrar nueva sesión
            \App\Models\LoginIp::create([
                'usuario_id' => $usuario->id,
                'ip_address' => $request->ip(),
                'session_token' => Session::getId()
            ]);

            return redirect()->route('welcome');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user && $user->usuario) {
            // Eliminar registro de sesión activa en BD
            \App\Models\LoginIp::where('usuario_id', $user->usuario->id)->delete();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    
    // Métodos para mostrar vistas se mantienen igual
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    
    // ... sendResetLink logic ... (Omitting for brevity or keep basic)
    public function sendResetLink(Request $request) {
        return back()->with('status', 'Funcionalidad de reset pendiente de configuración SMTP.');
    }
}