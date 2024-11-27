<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reservas extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'fecha_entrada',
        'fecha_salida',
        'aceptada',
        'habitacion_id',
        'num_huespedes',
        'fecha_reserva',
    ];
}
