<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registracion extends Model
{
    //
    protected $fillable = ['user_id','event_id','estado'];
    public function user(){ return $this->belongsTo(User::class); }
    public function event(){ return $this->belongsTo(Evento::class); }
}
