<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'precio_compra_unitario',
    ];

    // Relación muchos a uno con compras
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    // Relación muchos a uno con productos
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
