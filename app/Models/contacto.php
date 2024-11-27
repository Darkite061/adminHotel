<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contacto extends Model
{
    use HasFactory;

    // Define el nombre de la tabla
    protected $table = 'contacto';

    protected $fillable = 
    [
        'nombre',
        'correo',
        'asunto',
        'mensaje',
        'fecha_contacto',
        'atendida',
    ];
}
