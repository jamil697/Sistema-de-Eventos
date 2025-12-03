<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    
   protected $table = 'reportes';

    protected $fillable = [
        'tipoReporte',
        'fechaGeneracion',
        'contenido',
        'administrador_id'
    ];

    public function administrador()
    {
        return $this->belongsTo(User::class, 'administrador_id');
    }
}
