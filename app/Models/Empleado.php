<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'correo',
        'telefono',
        'direccion',
        'cargo',
        'fecha_contratacion',
        'fecha_egreso', // Nuevo campo
        'user_id',
    ];

    protected $casts = [
        'fecha_contratacion' => 'date',
        'fecha_egreso' => 'date', // Nuevo campo
    ];

    // Relación uno a uno (inversa) con users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación uno a muchos con compras
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    // Relación uno a muchos con ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
