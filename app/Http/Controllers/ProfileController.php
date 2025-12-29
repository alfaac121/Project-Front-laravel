<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $usuario = $user->usuario;
        return view('profile.index', compact('user', 'usuario'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $usuario = $user->usuario;

        $request->validate([
            'nickname' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'link' => 'nullable|url',
            // 'imagen' => 'nullable|image|max:2048' // Commented out until we handle file upload fully if requested
        ]);

        $usuario->update([
            'nickname' => $request->input('nickname'),
            'descripcion' => $request->input('descripcion'),
            'link' => $request->input('link'),
        ]);

        return redirect()->route('profile.index')->with('success', 'Perfil actualizado correctamente.');
    }
}
