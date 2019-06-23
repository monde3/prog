<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    /**
     * Atributos que son masivamente asignables
     *
     * @var array
     */
    protected $fillable = [
        'user_id','alumno_tarea_id','oponente_id','texto','resultado','activa'
    ];
    protected $table = 'notificaciones';
    
    /*
     * No guardamos cuando ha sido creado y/o actualizado un registro
     */
    public $timestamps = false;

    /**
     * Obtiene el usuario al que se le ha asignado la notificacion.
     */
    public function usuario()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Obtiene el usuario al que se le ha asignado la notificacion.
     */
    public function tarea()
    {
        return $this->belongsTo('App\AlumnoTarea', 'alumno_tarea_id');
    }

    /**
     * Obtiene el usuario al que se le ha asignado la notificacion.
     */
    public function oponente()
    {
        return $this->belongsTo('App\User', 'oponente_id');
    }
}
