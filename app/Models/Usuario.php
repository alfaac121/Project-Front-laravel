<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'cuenta_id',
        'nickname',
        'rol_id',
        'estado_id',
        'imagen',
        'descripcion',
        'link',
    ];

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'vendedor_id');
    }

    public function favoritos_dados()
    {
        return $this->hasMany(Favorito::class, 'votante_id');
    }

    public function favoritos_recibidos()
    {
        return $this->hasMany(Favorito::class, 'votado_id');
    }
    
    public function login_ips()
    {
        return $this->hasOne(LoginIp::class, 'usuario_id');
    }
}
