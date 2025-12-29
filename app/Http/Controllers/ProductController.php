<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show($id)
    {
        // Producto con vendedor (Usuario)
        $product = \App\Models\Producto::with('vendedor')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required|numeric',
            'disponibles' => 'required|integer|min:1'
        ]);

        \App\Models\Producto::create([
            'nombre' => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'precio' => $request->input('precio'),
            'disponibles' => $request->input('disponibles'),
            'vendedor_id' => Auth::user()->usuario->id, // Usuario ID
            'subcategoria_id' => 1, // Default simulado
            'integridad_id' => 1,   // Default simulado
            'estado_id' => 1        // Activo
        ]);

        return redirect()->route('welcome')->with('success', 'Producto publicado exitosamente.');
    }
}
