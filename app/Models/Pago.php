<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{

    protected $table = 'pagos';

    protected $fillable = [
        'monto',
        'fechaPago',
        'metodoPago',
        'estado',
        'inscripcion_id'
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }
}
