<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    /**
     * Atributos que son masivamente asignables
     *
     * @var array
     */
    protected $fillable = [
        'user_id','head','body','hands','foot','weapon',
    ];

    /**
     * Primary key
     */
    protected $primaryKey = 'user_id';

    protected $table = 'avatar';
    
    /*
     * No guardamos cuando ha sido creado y/o actualizado un registro
     */
    public $timestamps = false;

    /**
     * Obtiene la tarea del alumno a la que pertenece el tiempo
     */
    public function alumno()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function sumarHead($cantidad){
            $this->head += $cantidad;
            $this->save();
    }

    public function sumarBody($cantidad){
            $this->body += $cantidad;
            $this->save();
    }

    public function sumarHands($cantidad){
            $this->hands += $cantidad;
            $this->save();
    }

    public function sumarFoot($cantidad){
            $this->foot += $cantidad;
            $this->save();
    }

    public function sumarWeapon($cantidad){
            $this->weapon += $cantidad;
            $this->save();
    }

}
