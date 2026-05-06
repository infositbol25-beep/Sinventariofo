<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $table = 'entradas';

    protected $fillable = [
        'fecha',
        'tipo_ingreso',
        'proveedor',
        'documento_referencia',
        'observaciones',
        'user_id',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleEntrada::class, 'entrada_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}