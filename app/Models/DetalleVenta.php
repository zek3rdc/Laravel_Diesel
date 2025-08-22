<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_venta_unitario',
    ];

    // Relación muchos a uno con ventas
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    // Relación muchos a uno con productos
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
