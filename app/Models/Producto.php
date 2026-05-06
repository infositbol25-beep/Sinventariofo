<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'nombre',
        'categoria_id',
        'unidad_medida',
        'stock_actual',
        'stock_minimo',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'stock_actual' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function detalleEntradas()
    {
        return $this->hasMany(DetalleEntrada::class, 'producto_id');
    }

    public function detalleSalidas()
    {
        return $this->hasMany(DetalleSalida::class, 'producto_id');
    }

    public function detalleDevoluciones()
    {
        return $this->hasMany(DetalleDevolucion::class, 'producto_id');
    }
}