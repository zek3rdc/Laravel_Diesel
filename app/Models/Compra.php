<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nro_factura_proveedor',
        'proveedor_id',
        'empleado_id',
        'fecha_compra',
        'monto_total',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_compra' => 'datetime',
    ];

    // Relación muchos a uno con proveedores
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Relación muchos a uno con empleados
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Relación uno a muchos con detalle_compras
    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }
}
