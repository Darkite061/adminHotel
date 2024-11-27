<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pagos extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserva_id',
        'monto',
        'metodo_pago',
        'completado',
        'fecha_pago',
    ];
}
