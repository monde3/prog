<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AlumnoTarea;
use App\Tarea;
use Carbon\Carbon;

class MisTareasFinalizadasController extends Controller
{
    /**
     * Pantalla de las tareas activas de un alumno/profesor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if (!$request->user()->activo){
            abort(403);
        }elseif ($request->user()->rol == 'alumno'){
            $tareas = AlumnoTarea::where('user_id', $request->user()->id)->get();
            
            $tareas = $tareas->reject(function ($tareaAlumno) {
                return $tareaAlumno->tarea->fecha_fin > Carbon::now('Europe/Madrid');
            });
        } elseif ($request->user()->rol == 'profesor'){
            $tareas = Tarea::where('propietario_id', $request->user()->id)->get();
            
            $tareas = $tareas->reject(function ($tarea) {
                return $tarea->fecha_fin > Carbon::now('Europe/Madrid');
            });

        }
        
        return view('misfinalizadas', compact('tareas'));
    }
}
