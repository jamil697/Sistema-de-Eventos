<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
  
    protected $table = 'inscripciones';

    protected $fillable = [
        'fechaInscripcion',
        'estado',
        'user_id',
        'evento_id'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function pago()
    {
        return $this->hasOne(Pago::class);
    }
}
