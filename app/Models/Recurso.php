<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{

    protected $fillable = ['nombre','descripcion','cantidad'];
    public function events()
    {
        return $this->belongsToMany(
            Evento::class,
            'evento_recurso',
            'recurso_id',  // columna en pivot que referencia a resources
            'event_id'      // columna en pivot que referencia a eventos
        )->withPivot('cantidad')->withTimestamps();
    }

}
