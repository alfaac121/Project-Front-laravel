<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        // Si el usuario está logueado, redirigir
        if (Auth::check()) {
            return redirect()->route('home');
        }

        // Forzar modo claro (se maneja en JS)
        return view('welcome');
    }
}
