<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nro_factura',
        'cliente_id',
        'empleado_id',
        'fecha_venta',
        'sub_total',
        'impuesto',
        'total',
        'metodo_pago',
        'moneda_pago',
        'referencia_pago',
        'estado',
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
    ];

    // Relación muchos a uno con clientes
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación muchos a uno con empleados
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Relación uno a muchos con detalle_ventas
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
