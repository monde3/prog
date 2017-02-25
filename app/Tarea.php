<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cod_tarea', 'titulo', 'des_tarea', 'tiempo_estimado', 'fecha_fin', 'propierio_id', 'cod_asi',
    ];
}
