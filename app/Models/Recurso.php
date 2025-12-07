<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recurso extends Model
{
    use HasFactory;
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
