<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Ensure we import Product

class HomeController extends Controller
{
    /**
     * Show the application dashboard / home.
     */
    public function index()
    {
        // Obtener productos activos (estado_id = 1) con su vendedor (Usuario)
        $products = \App\Models\Producto::where('estado_id', 1)
                    ->with('vendedor')
                    ->latest()
                    ->get();

        return view('home', compact('products'));
    }
}
