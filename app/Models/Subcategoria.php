<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria_id',
    ];

    // Relación muchos a uno con categorías
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación uno a muchos con productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
