<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class facturas extends Model
{
    use HasFactory;

    protected $fillable = [
        'pago_id',
        'cliente_id',
        'fecha_emision',
        'detalles',
        'monto_total',
        'numero_factura',
    ];
}
