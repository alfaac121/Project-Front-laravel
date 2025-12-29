<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'disponibles',
        'vendedor_id',
        'subcategoria_id',
        'integridad_id',
        'estado_id',
    ];

    public function vendedor()
    {
        return $this->belongsTo(Usuario::class, 'vendedor_id');
    }
}
