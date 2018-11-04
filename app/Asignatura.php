<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    /**
     * Atributos que son masivamente asignables
     *
     * @var array
     */
    protected $fillable = [
        'cod_asi', 'des_asi',
    ];

    protected $primaryKey = 'cod_asi';
    public $timestamps = false;

    /**
     * Obtiene las tareas de cada asignatura.
     */
    public function tareas()
    {
        return $this->hasMany('App\Tarea');
    }
}
