<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;
use App\AlumnoTarea;

class Tarea extends Model
{

    /**
     * Atributos que son masivamente asignables
     *
     * @var array
     */
    protected $fillable = [
        'cod_tarea', 'titulo', 'des_tarea', 'curso_academico', 'tiempo_estimado', 'fecha_fin', 'propietario_id', 'cod_asi',
    ];

    /**
     * Primary key
     */
    protected $primaryKey = 'cod_tarea';

    /*
     * No guardamos cuando ha sido creado y/o actualizado un registro
     */
    public $timestamps = false;

    /*
     Constantes para saber cuantos segundos deben de faltar para que una tarea entre en alerta
     */
    const ALERTA_ROJA = 10080; //Minutos restantes de una tarea para que esté en alerta roja
    const ALERTA_AMARILLA = 30240; //Minutos restantes de una tarea para que esté en alerta amarilla
    
    /**
     * Obtiene el usuario propietario de la tarea
     */
    public function propietario()
    {
        return $this->belongsTo('App\User', 'propietario_id');
    }

    /**
     * Obtiene la asignatura a la que pertenece la tarea.
     */
    public function asignatura()
    {
        return $this->belongsTo('App\Asignatura', 'cod_asi', 'cod_asi');
    }


    /**
     * Obtiene los alumnos que cursan una tarea
     */
    public function alumnos()
    {
        return $this->hasMany('App\AlumnoTarea', 'cod_tarea', 'cod_tarea');
    }


    /**
     * Obtiene la fecha final formateada d/m/Y H:i
     */
    public function fechaFormateada(){

        $fechaFinTarea = Carbon::parse($this->fecha_fin, 'Europe/Madrid');
        return $fechaFinTarea->format('d/m/Y H:i');
        
    }

    /**
     * Obtiene el tiempo estimado formateado
     */
    public function tiempoTareaFormateado(){

        $minutos = $this->tiempo_estimado;
        $horas = 0;
        $dias = 0;
        $semanas = 0;

        $salida = '';

        $horas = floor($minutos/Carbon::MINUTES_PER_HOUR);

        if ($horas > 0){

            $minutos = $minutos - ($horas * Carbon::MINUTES_PER_HOUR);

            $dias = floor($horas/Carbon::HOURS_PER_DAY);

            if ($dias > 0){
                $horas = $horas - ($dias * Carbon::HOURS_PER_DAY);

                $semanas = floor($dias/Carbon::DAYS_PER_WEEK);

                if ($semanas > 0){
                    $dias = $dias - ($semanas * Carbon::DAYS_PER_WEEK);                    
                }
            }

        }


        if ($minutos > 0){
           $salida = $minutos . 'm';
        }

        if ($horas > 0){
           $salida = $horas . 'h ' . $salida;
        }

        if ($dias > 0){
           $salida = $dias . 'd ' . $salida;
        }

        if ($semanas > 0){
           $salida = $semanas . 'w ' . $salida;
        }

        return $salida;
    }

    /**
     * Obtiene el tiempo que le falta a la tarea por acabar
     */
    public function tiempoRestante(){

        $fechaFinTarea = Carbon::parse($this->fecha_fin, 'Europe/Madrid');
        $ahora =  Carbon::parse('now', 'Europe/Madrid');

        if ($fechaFinTarea < $ahora){
            return 0;
        }

        $minutos = $fechaFinTarea->diffInMinutes($ahora);
        
        return $minutos;
    }

    /**
     * Obtiene el tiempo que le falta a la tarea por acabar formateado
     */
    public function tiempoRestanteFormateado (){
        
        $minutos = $this->tiempoRestante();
        $horas = 0;
        $dias = 0;
        $semanas = 0;

        $salida = '';

        $horas = floor($minutos/Carbon::MINUTES_PER_HOUR);

        if ($horas > 0){

            $minutos = $minutos - ($horas * Carbon::MINUTES_PER_HOUR);

            $dias = floor($horas/Carbon::HOURS_PER_DAY);

            if ($dias > 0){
                $horas = $horas - ($dias * Carbon::HOURS_PER_DAY);

                $semanas = floor($dias/Carbon::DAYS_PER_WEEK);

                if ($semanas > 0){
                    $dias = $dias - ($semanas * Carbon::DAYS_PER_WEEK);                    
                }
            }

        }

        if ($minutos > 0){
            if ($minutos == 0){
                $salida = $minutos . ' minuto';     
            }else{
                $salida = $minutos . ' minutos';
            }           
        }

        if ($horas > 0){

            if ($minutos > 0){
                $salida = ' y ' . $salida;       
            }

            if ($horas == 1){
                $salida = $horas . ' hora' . $salida;    
            }else{
                $salida = $horas . ' horas' . $salida;
            }
        }
        

        if ($dias > 0){

            if ($minutos > 0 and $horas > 0){
                $salida = ', ' . $salida;
            }elseif ($minutos > 0 or $horas > 0) {
                $salida = ' y ' . $salida;
            }

            if ($dias == 1){
                $salida = $dias . ' día' . $salida;
            }else{
                $salida = $dias . ' días' . $salida;
            }
        }

        if ($semanas > 0){
            if (($minutos > 0 and $horas > 0 and $dias > 0) or 
                ($minutos > 0 and $horas > 0) or
                ($minutos > 0 and $dias > 0) or
                ($horas > 0 and $dias > 0)){
                
                $salida = ', ' . $salida;
            }elseif ($minutos > 0 or $horas > 0 or $dias > 0) {
                $salida = ' y ' . $salida;
            }

            if ($semanas == 1){
                $salida = $semanas . ' semana' . $salida;
            }else{
                $salida = $semanas . ' semanas' . $salida;    
            }
        }

        return $salida;
    }

    /**
     * Obtiene si la tarea ha acabado
     */
    function activa (){

        return (Carbon::parse($this->fecha_fin, 'Europe/Madrid') > Carbon::parse('now', 'Europe/Madrid'));
    }

    /**
     * Obtiene la lista de alumnos de una tarea
     */
    function ranking(){
        $alumnos = AlumnoTarea::where('cod_tarea', str_pad($this->cod_tarea, 10, "0", STR_PAD_LEFT));

        return $alumnos;
    }

    function mediaTiempoTrabajado (){
        $alumnos = $this->alumnos;

        $media = $alumnos->reject(function ($alumno) {
            return $alumno->tiempoTotalSegundos()==0;
        })->map(function ($alumno) {
            return $alumno->tiempoTotalSegundos();
        })->avg();

        if (!isset($media)){
            return 0;
        }

        return $media;
    }




}
