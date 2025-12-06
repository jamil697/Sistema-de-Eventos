<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evento extends Model {
    protected $fillable = ['titulo','descripcion','lugar','fecha_inicio','categoria_id','fecha_fin','cupo','created_by'];

    public function registrations()
{
    // la foreign key en la tabla registrations es 'event_id'
    return $this->hasMany(Registracion::class, 'event_id');
}

public function users()
{
    // relación many-to-many vía tabla registrations
    return $this->belongsToMany(
        \App\Models\User::class,
        'registrations',
        'event_id',   // foreign key en la tabla registrations que apunta a eventos
        'user_id'     // foreign key en la tabla registrations que apunta a users
    )->withTimestamps()->withPivot('estado','id');
}

    public function resources()
    {
        // tabla pivot, foreignPivotKey en esta tabla (event_id), relatedPivotKey (resource_id)
        return $this->belongsToMany(
            Recurso::class,
            'evento_recursos',
            'event_id',     // columna en pivot que referencia a eventos
            'recursos_id'   // columna en pivot que referencia a resources
        )->withPivot('cantidad')->withTimestamps();
    }


    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

}

