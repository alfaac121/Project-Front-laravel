<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    use HasFactory;

    protected $table = 'correos';

    protected $fillable = [
        'correo',
    ];

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'correo_id');
    }
}
