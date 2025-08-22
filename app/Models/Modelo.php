<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'marca_id',
    ];

    // Relación muchos a uno con marcas
    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    // Relación muchos a muchos con productos
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_modelo');
    }
}
