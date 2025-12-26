<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;

// Ruta principal (página de bienvenida sin autenticar)
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Rutas de autenticación - LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');

// Ruta de registro
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

// Ruta para cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Ruta protegida (dashboard después de login exitoso)
Route::get('/welcome', function () {
    // Auth middleware ya maneja la redirección si no está logueado
    return view('dashboard.index'); // o la vista que uses como dashboard
})->name('welcome')->middleware('auth');

// Rutas para recuperar contraseña
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot.password')->middleware('guest');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('guest');