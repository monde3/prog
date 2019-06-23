<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AvatarImagen extends Model
{
    /**
     * Atributos que son masivamente asignables
     *
     * @var array
     */
    protected $fillable = ['avatar_id', 'imagen_id'];

    protected $table = 'avatar_imagenes';
    
    /*
     * No guardamos cuando ha sido creado y/o actualizado un registro
     */
    public $timestamps = false;

    /**
     * Obtiene la imagen que se ha asignado al avatar.
     */
    public function imagen()
    {
        return $this->belongsTo('App\Imagen', 'imagen_id');
    }

}
