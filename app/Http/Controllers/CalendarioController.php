<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\AlumnoTarea;
use App\Tarea;

class CalendarioController extends Controller
{
    /**
     * Devuelve un json con los datos de las tareas de un alumno/profesor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {   

        if (!$request->user()->activo){

        }elseif ($request->user()->rol == 'alumno') {
            $tareas = AlumnoTarea::where('user_id', $request->user()->id)->get();
            
            $tareas = $tareas->map(function ($tarea) {
                $color = "";
                if (Carbon::parse($tarea->tarea->fecha_fin,'Europe/Madrid') < Carbon::parse('now','Europe/Madrid')){
                    $color = "black";
                }elseif ($tarea->tarea->tiempoRestante() < Tarea::ALERTA_ROJA){
                  //menos que una semana
                  $color = "red";
                }elseif($tarea->tarea->tiempoRestante() < Tarea::ALERTA_AMARILLA){
                  //menos que tres semanas
                  $color = "#EFEF2D";              
                }
                return [
                        'id' => $tarea->cod_tarea,
                        'title' => $tarea->tarea->titulo,
                        'start' => $tarea->tarea->fecha_fin,
                        'color' => $color
                       ];
            });
        } elseif ($request->user()->rol == 'profesor'){
            $tareas = Tarea::where('propietario_id', $request->user()->id)->get();
            
            $tareas = $tareas->map(function ($tarea) {
                $color = "";
                if (Carbon::parse($tarea->fecha_fin,'Europe/Madrid') < Carbon::parse('now','Europe/Madrid')){
                    $color = "black";
                }elseif ($tarea->tiempoRestante() < Tarea::ALERTA_ROJA){
                  //menos que una semana
                  $color = "red";
                }elseif($tarea->tiempoRestante() < Tarea::ALERTA_AMARILLA){
                  //menos que tres semanas
                  $color = "#EFEF2D";              
                }
                return [
                        'id' => $tarea->cod_tarea,
                        'title' => $tarea->titulo,
                        'start' => $tarea->fecha_fin,
                        'color' => $color
                       ];
            });
        }

       return Response()->json($tareas);
    
    }
}
