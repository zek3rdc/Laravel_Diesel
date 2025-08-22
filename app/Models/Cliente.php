<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'direccion',
    ];

    // Relación uno a muchos con ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
