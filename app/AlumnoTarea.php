<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\TiempoTarea;


class AlumnoTarea extends Model
{
    /**
     * Atributos que son masivamente asignables
     *
     * @var array
     */
    protected $fillable = ['user_id', 'cod_tarea', 'completada'];

    /*
     * No guardamos cuando ha sido creado y/o actualizado un registro
     */
    public $timestamps = false;

    /*
     * Estados de las tareas de un alumno
     */
    const ACTIVA = "Activa";
    const EN_PROGRESO = "En progreso";
    const FINALIZADA = "Finalizada";
    const COMPLETADA = "Completada";
    const ERROR = "Error";
    
    /**
     * Obtiene el alumno al que se le ha asignado la tarea.
     */
    public function alumno()
    {
        return $this->belongsTo('App\User', 'user_id');
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
    public function tiempoTotalSegundos(){
        
        $tiempoTotal = 0;
        $tiemposTarea = TiempoTarea::where('alumno_tarea_id', $this->id)->get();
        
        foreach ($tiemposTarea as $tiempo) {
            $tiempoTotal = $tiempoTotal + $tiempo->tiempoParcial();
        }

        Return $tiempoTotal;
    }

    /**
     * Devuelve el tiempo que se ha trabajado en la tarea en minutos
     */
    public function tiempoTotal(){
        
        $tiempoTotal = $this->tiempoTotalSegundos();
        
        $tiempoTotal = floor($tiempoTotal/Carbon::SECONDS_PER_MINUTE);

        Return $tiempoTotal;
    }

    /**
     * Devuelve el porcentaje del tiempo realizado según la estimación del profesor
     */
    public function porcentaje(){
        
        $tiempoTotal = $this->tiempoTotal();
        
        $portentaje = ($tiempoTotal*100)/$this->tarea->tiempo_estimado;

        return $portentaje;
    }

    /**
     * Devuelve el estado de la tarea de un alumno
     */
    public function estado(){
        $fechaFinTarea = Carbon::parse($this->tarea->fecha_fin, 'Europe/Madrid');        
        $ahora =  Carbon::parse('now', 'Europe/Madrid');

        //(amondejar)
        // Completada
        if (!$this->completada) {
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
        }else{
            return Self::COMPLETADA;
        }
        
    }

    /**
     * Devuelve el tiempo total de una tarea de un alumno formateado
     */
    public function tiempoTotalFormateado(){

        $segundos = $this->tiempoTotalSegundos();;

        if ($segundos == 0){
            return '0m';
        }

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

        return $salida;
    }

    /**
     * Devuelve el ranking del alumno en la tarea
     */
    public function miRanking(){
        $listaAlumnos = $this->tarea->alumnos;
        $tiempo = $this->tiempoTotal();

        $alumnosMejores = $listaAlumnos->reject(function ($alumno) use ($tiempo) {
            return $alumno->tiempoTotal()<=$tiempo;
        });

        return ($alumnosMejores->count()+1);
    }

    /**
     * Iniciar progreso
     * Devuelve si se ha podido iniciar o no
     */
    public function iniciar(){
        $fechaFinTarea = Carbon::parse($this->tarea->fecha_fin, 'Europe/Madrid');
        $ahora = Carbon::now('Europe/Madrid');

        if ($this->estado() === AlumnoTarea::ACTIVA and $fechaFinTarea > $ahora) {
            $tiempo = new TiempoTarea;
            $tiempo->alumno_tarea_id = $this->id;
            $tiempo->inicio = Carbon::now('Europe/Madrid');
            $tiempo->save();
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Parar progreso
     */
    public function parar(){
        $fechaFinTarea = Carbon::parse($this->tarea->fecha_fin, 'Europe/Madrid');
        $ahora = Carbon::now('Europe/Madrid');

        if ($this->estado() === AlumnoTarea::EN_PROGRESO) {
            $miTiempoActivo = TiempoTarea::where('alumno_tarea_id', $this->id)->whereNull('fin')->first();

            if ($fechaFinTarea > $ahora) {
                $miTiempoActivo->fin = $ahora;
            } else {
                $miTiempoActivo->fin = $fechaFinTarea;
            }

            $miTiempoActivo->save();
        }
    }

    /**
     * (amondejar)
     * Completar tarea
     * @return 1 si se ha realizado, 0 si no se ha realizado
     */
    public function completar(){
        if (($this->estado() == Self::ACTIVA) and ($this->alumno->rol == 'alumno')){
            $this->completada = 1;
            $this->save();
            $sube_nivel = $this->alumno->avatar->sumarExperiencia(15);
            $this->alumno->avatar->sumarOro(50);
            return 1 + $sube_nivel;
        }
        return 0;
    }

}
