<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    /**
     * Atributos que son masivamente asignables
     *
     * @var array
     */
    protected $fillable = [
        'parte','precio','filename'
    ];

    protected $table = 'imagenes';
    
    /*
     * No guardamos cuando ha sido creado y/o actualizado un registro
     */
    public $timestamps = false;
}
