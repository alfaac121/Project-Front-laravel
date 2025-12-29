<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginIp extends Model
{
    use HasFactory;

    protected $table = 'login_ip';

    protected $fillable = [
        'usuario_id',
        'ip_address',
        'session_token',
        'last_activity',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
