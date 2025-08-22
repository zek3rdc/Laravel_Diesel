<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores'; // Especificar el nombre de la tabla

    protected $fillable = [
        'nombre',
        'ruc',
        'correo',
        'telefono',
        'direccion',
        'persona_contacto',
    ];

    // Relación uno a muchos con productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    // Relación uno a muchos con compras
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }
}
