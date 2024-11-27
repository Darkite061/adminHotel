<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class opiniones extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'comentario',
        'calificacion',
        'aprobado',
        'fecha_opinion',
    ];
}
