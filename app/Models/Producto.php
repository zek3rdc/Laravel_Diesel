<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo_sku',
        'foto_url',
        'precio_venta',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
        'estado',
        'fecha_vencimiento',
        'categoria_id',
        'subcategoria_id',
        'proveedor_id',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    // Relación muchos a uno con subcategorías
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class);
    }

    // Relación muchos a uno con proveedores
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // Relación muchos a muchos con modelos
    public function modelos()
    {
        return $this->belongsToMany(Modelo::class, 'producto_modelo');
    }

    // Relación uno a muchos con detalle_compras
    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    // Relación uno a muchos con detalle_ventas
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
