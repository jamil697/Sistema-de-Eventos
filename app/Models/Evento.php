<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Evento extends Model
{

    protected $table = 'eventos';

    protected $fillable = [
        'titulo',
        'tipo',
        'fechaInicio',
        'fechaFin',
        'ubicacion',
        'capacidad',
        'esDePago',
        'estado',
        'costo',
        'administrador_id'
    ];

    public function administrador()
    {
        return $this->belongsTo(User::class, 'administrador_id');
    }

    public function recursos()
    {
        return $this->belongsToMany(Recurso::class, 'evento_recurso')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }
}
