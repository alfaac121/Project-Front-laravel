<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorito extends Model
{
    use HasFactory;

    protected $table = 'favoritos';

    protected $fillable = [
        'votante_id',
        'votado_id',
    ];

    public function votante()
    {
        return $this->belongsTo(Usuario::class, 'votante_id');
    }

    public function votado()
    {
        return $this->belongsTo(Usuario::class, 'votado_id');
    }
}
