<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoRecurso extends Model
{
      protected $table = 'evento_recurso';

    protected $fillable = [
        'evento_id',
        'recurso_id',
        'cantidad'
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function recurso()
    {
        return $this->belongsTo(Recurso::class);
    }
}
