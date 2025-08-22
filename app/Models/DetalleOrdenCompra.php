<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrdenCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_orden_compra';

    protected $fillable = [
        'orden_compra_id',
        'producto_id',
        'cantidad',
    ];

    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
