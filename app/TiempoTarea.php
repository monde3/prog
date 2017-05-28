<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TiempoTarea extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alumno_tarea_id', 'inicio', 'fin',
    ];
    
    
    /**
     * Obtiene la tarea del alumno a la que pertenece el tiempo
     */
    public function alumnoTarea()
    {
        return $this->belongsTo('App\AlumnoTarea');
    }

    public function fechaInicioFormateada(){

        $fechaInicio = Carbon::parse($this->inicio, 'Europe/Madrid');
        return $fechaInicio->format('d/m/Y H:i');

    }

    public function fechaFinFormateada(){

        if (isset($this->fin)){
            $fechaFin = Carbon::parse($this->fin, 'Europe/Madrid');
            return $fechaFin->format('d/m/Y H:i');
        }else{
            return '';
        }

        
    }

    /**
     * Devuelve el tiempo en segundos de este registro
     */
    public function tiempoParcial(){
        $fechaFinTarea = Carbon::parse($this->alumnoTarea->tarea->fecha_fin, 'Europe/Madrid');        
        $ahora =  Carbon::parse('now', 'Europe/Madrid');
        $inicio = Carbon::parse($this->inicio, 'Europe/Madrid');
        $tiempo = 0;

        if (isset($this->fin)){
            $fin = Carbon::parse($this->fin, 'Europe/Madrid');
            $tiempo = $fin->diffInSeconds($inicio);
        }else{
            if ($fechaFinTarea > $ahora){                
                $fin = $ahora;
            }else{
                $fin = $fechaFinTarea;
            }
            $tiempo = $fin->diffInSeconds($inicio);
        }

        return $tiempo;
    }

    public function tiempoParcialFormateado(){
        $segundos = $this->tiempoParcial();;
        $minutos = 0;
        $horas = 0;
        $dias = 0;
        $semanas = 0;

        $salida = '';

        $minutos = floor($segundos/Carbon::SECONDS_PER_MINUTE);

        if ($minutos > 0){

            $segundos = $segundos - ($minutos * Carbon::SECONDS_PER_MINUTE);

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
        }


        if ($segundos > 0){
           $salida = $segundos . 's';
        }

        if ($minutos > 0){
           $salida = $minutos . 'm ' . $salida;
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

        if ($segundos==0 and $minutos==0 and $horas==0 and $dias==0 and $semanas==0){
             $salida = $segundos . 's';
        }
        return $salida;
    }


}
