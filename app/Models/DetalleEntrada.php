<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleEntrada extends Model
{
    use HasFactory;

    protected $table = 'detalle_entradas';

    protected $fillable = [
        'entrada_id',
        'producto_id',
        'cantidad',
        'costo_referencial',
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'costo_referencial' => 'decimal:2',
    ];

    public function entrada()
    {
        return $this->belongsTo(Entrada::class, 'entrada_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}