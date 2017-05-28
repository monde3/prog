<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\TiempoTarea;


class AlumnoTarea extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'cod_tarea', 'curso_academico'];

    public $timestamps = false;

    const ACTIVA = "Activa";
    const EN_PROGRESO = "En progreso";
    const FINALIZADA = "Finalizada";
    const ERROR = "Error";
    
    /**
     * Obtiene el alumno al que se le ha asignado la tarea.
     */
    public function alumno()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Obtiene la tarea que se ha asignado al alumno.
     */
    public function tarea()
    {
        return $this->belongsTo('App\Tarea', 'cod_tarea', 'cod_tarea');
    }

    /**
     * Obtiene los tiempos de la tarea.
     */
    public function tiemposTareas()
    {
        return $this->hasMany('App\TiempoTarea');
    }

    /**
     * Devuelve el tiempo que se ha trabajado en la tarea en minutos
     */

    public function tiempoTotal(){
        
        $tiempoTotal = 0;
        $tiemposTarea = TiempoTarea::where('alumno_tarea_id', $this->id)->get();
        
        foreach ($tiemposTarea as $tiempo) {
            $tiempoTotal = $tiempoTotal + $tiempo->tiempoParcial();
        }

        $tiempoTotal = floor($tiempoTotal/Carbon::SECONDS_PER_MINUTE);

        Return $tiempoTotal;
    }

    public function porcentaje(){
        
        $tiempoTotal = $this->tiempoTotal();
        
        $portentaje = ($tiempoTotal*100)/$this->tarea->tiempo_estimado;

        return $portentaje;
    }

    public function estado(){
        $fechaFinTarea = Carbon::parse($this->tarea->fecha_fin, 'Europe/Madrid');        
        $ahora =  Carbon::parse('now', 'Europe/Madrid');

        if ($fechaFinTarea < $ahora){
            return AlumnoTarea::FINALIZADA;
        }else{
            $numTareasActivas = TiempoTarea::where('alumno_tarea_id', $this->id)->whereNull('fin')->count();
            if ($numTareasActivas===0){
                return AlumnoTarea::ACTIVA;
            }elseif ($numTareasActivas===1){
                return AlumnoTarea::EN_PROGRESO;
            }
            return AlumnoTarea::ERROR;
            
        }
        
    }

    public function accion(){

        $numTareasActivas = TiempoTarea::where('alumno_tarea_id', $this->id)->whereNull('fin')->count();

        if ($numTareasActivas===0){
            return "Iniciar";
        }elseif ($numTareasActivas===1){
            return "Parar";
        }

        return "Error";
    }

    public function tiempoTotalFormateado(){

        $minutos = $this->tiempoTotal();;
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


    public function miRanking(){
        $listaAlumnos = $this->tarea->alumnos;
        $tiempo = $this->tiempoTotal();
        

        $alumnosMejores = $listaAlumnos->reject(function ($alumno, $tiempo) {
            return $alumno->tiempoTotal()>=$tiempo;
        });

        return ($alumnosMejores->count() + 1);
    }


}
