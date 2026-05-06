<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    use HasFactory;

    protected $table = 'devoluciones';

    protected $fillable = [
        'fecha',
        'salida_id',
        'observaciones',
        'user_id',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleDevolucion::class, 'devolucion_id');
    }

    public function salida()
    {
        return $this->belongsTo(Salida::class, 'salida_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}