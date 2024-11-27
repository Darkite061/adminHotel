<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    use HasFactory;

    protected $table = 'tipo_habitaciones';

    protected $fillable = [
        'tipo',
        'descripcion',
        'imagen_url',
        'precio',
    ];
}

