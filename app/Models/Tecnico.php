<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tecnico extends Model
{
    use HasFactory;

    protected $table = 'tecnicos';

    protected $fillable = [
        'nombre_completo',
        'ci',
        'telefono',
        'cargo',
        'cuadrilla',
        'zona',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function salidas()
    {
        return $this->hasMany(Salida::class, 'tecnico_id');
    }
}