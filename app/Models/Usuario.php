<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'correo_id',
        'password',
        'estado_id',
        'rol_id',
    ];

    public function correo()
    {
        return $this->belongsTo(Correo::class, 'correo_id');
    }

    // Accessor para que Laravel pueda obtener el email (necesario para reset password nativo)
    public function getEmailAttribute()
    {
        return $this->correo ? $this->correo->correo : null;
    }
}
