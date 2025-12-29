<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cuenta extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'cuentas';

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'cuenta_id');
    }
}
