<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null;
    const CREATED_AT = 'fecha_log';

    protected $fillable = [
        'producto_id',
        'user_id',
        'accion',
        'datos_viejos',
        'datos_nuevos',
    ];

    protected $casts = [
        'datos_viejos' => 'array',
        'datos_nuevos' => 'array',
    ];

    // Relación muchos a uno con productos
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Relación muchos a uno con users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
