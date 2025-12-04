<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{

    protected $table = 'recursos';

    protected $fillable = [
        'nombre',
        'tipo',
        'cantidad',
        'disponibilidad'
    ];

    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'evento_recurso')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

}
