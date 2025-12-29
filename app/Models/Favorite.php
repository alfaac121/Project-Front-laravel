<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_id'
    ];

    public function user()
    {
        // The user who favorited (Usuario)
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function seller()
    {
        // The seller being favorited (Usuario)
        return $this->belongsTo(Usuario::class, 'seller_id');
    }
}
