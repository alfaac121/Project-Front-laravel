<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Usuario; // Usamos Usuario
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        // Obtener favoritos donde yo soy el votante
        // favorites_dados relación en Usuario
        $user = Auth::user()->usuario;
        $favorites = $user->favoritos_dados()->with('votado')->get();
        
        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:usuarios,id'
        ]);

        $sellerId = $request->input('seller_id');
        $user = Auth::user()->usuario; // Mi usuario

        if ($user->id == $sellerId) {
            return back()->with('error', 'No puedes agregarte a ti mismo.');
        }

        // Evitar duplicados (votante_id, votado_id)
        $exists = \App\Models\Favorito::where('votante_id', $user->id)
                    ->where('votado_id', $sellerId)
                    ->exists();

        if ($exists) {
            return back()->with('info', 'Ya está en tus favoritos.');
        }

        \App\Models\Favorito::create([
            'votante_id' => $user->id,
            'votado_id' => $sellerId
        ]);

        return back()->with('success', 'Vendedor agregado a favoritos.');
    }

    public function destroy($seller_id)
    {
        $user = Auth::user()->usuario;
        
        \App\Models\Favorito::where('votante_id', $user->id)
            ->where('votado_id', $seller_id)
            ->delete();
        
        return back()->with('success', 'Eliminado de favoritos.');
    }
}
