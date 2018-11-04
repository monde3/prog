<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TiempoTarea extends Model
{
    /**
     * Atributos que son masivamente asignables
     *
     * @var array
     */
    protected $fillable = [
        'alumno_tarea_id', 'inicio', 'fin',
    ];
   
    /*
     Constantes del estado de un progreso de una alumno en una tarea
     */
    const EN_PROGRESO = "En progreso";
    const FINALIZADA = "Finalizada"; 
    
    /**
     * Obtiene la tarea del alumno a la que pertenece el tiempo
     */
    public function alumnoTarea()
    {
        return $this->belongsTo('App\AlumnoTarea');
    }

    /*
     * Obtiene la fecha de inicio del progreso formateada
     */
    public function fechaInicioFormateada(){

        $fechaInicio = Carbon::parse($this->inicio, 'Europe/Madrid');
        return $fechaInicio->format('d/m/Y H:i:s');

    }

    /*
     * Obtiene la fecha de fin del progreso formateada
     */
    public function fechaFinFormateada(){

        if (isset($this->fin)){
            $fechaFin = Carbon::parse($this->fin, 'Europe/Madrid');
            return $fechaFin->format('d/m/Y H:i:s');
        }else{
            return '';
        }        
    }

    /*
     * Obtiene la fecha de fin real del progreso formateada
     */
    public function fechaFinReal (){
        $fechaFinTarea = Carbon::parse($this->alumnoTarea->tarea->fecha_fin, 'Europe/Madrid');        
        $ahora =  Carbon::parse('now', 'Europe/Madrid');
        if (isset($this->fin)){
            $fin = Carbon::parse($this->fin, 'Europe/Madrid');            
        }else{
            if ($fechaFinTarea > $ahora){
                $fin = $ahora;
            }else{
                $fin = $fechaFinTarea;
            }
        }

        return $fin;
    }


    /**
     * Obtiene el tiempo en segundos del progreso
     */
    public function tiempoParcial(){
        
        $inicio = Carbon::parse($this->inicio, 'Europe/Madrid');
        $fin = $this->fechaFinReal();
        $tiempo = 0;

        $tiempo = $fin->diffInSeconds($inicio);
        
        return $tiempo;
    }

    /**
     * Obtiene el tiempo en segundos del progreso formateado
     */
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

    /**
     * Obtiene el tiempo en segundos del progreso en un mes de un año
     */
    public function tiempoParcialMes($mes, $anyo){
        $inicio = Carbon::parse($this->inicio, 'Europe/Madrid');
        $fin = $this->fechaFinReal();
        $primeroMes = Carbon::create($anyo, $mes, 1, 0, 0, 0, 'Europe/Madrid');
        $ultimoMes = Carbon::create($anyo, $mes, 1, 0, 0, 0, 'Europe/Madrid')->endOfMonth();
        
        if ($primeroMes <= $inicio and $ultimoMes >= $inicio and
            $primeroMes <= $fin and $ultimoMes >= $fin){
            return $fin->diffInSeconds($inicio);
        }elseif ($primeroMes <= $inicio and $ultimoMes >= $inicio){
            return $ultimoMes->diffInSeconds($inicio);
        }elseif ($primeroMes <= $fin and $ultimoMes >= $fin){
            return $fin->diffInSeconds($primeroMes);
        }elseif ($inicio <= $primeroMes and $fin >= $primeroMes and
                 $inicio <= $ultimoMes and $fin >= $ultimoMes) {
            return $ultimoMes->diffInSeconds($primeroMes);
        }
        
        return 0;

    }

    /**
     * Obtiene el estado de un progreso
     */
    public function estado(){
        if (isset($this->fin)){
            return TiempoTarea::FINALIZADA;

        }else{
            return TiempoTarea::EN_PROGRESO;
        }
    }


}
