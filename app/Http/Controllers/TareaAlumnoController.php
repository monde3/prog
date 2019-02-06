<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tarea;
use App\AlumnoTarea;
use App\TiempoTarea;
use Carbon\Carbon;

class TareaAlumnoController extends Controller
{
    /**
     * Pantalla del detalle de la tarea de un alumno
     *
     * @param  int $alumno_tarea_id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($alumno_tarea_id, Request $request)
    {
        if (!$request->user()->activo){
            abort(403);
        }
        
        $tarea = AlumnoTarea::find($alumno_tarea_id);
        
        return view('tarea', compact('tarea'));
    }

    /**(amondejar)
     * Marca una tarea como completada si su estado es ACTIVA
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $alumno_tarea_id
     * @return \Illuminate\Http\Response
     */
    public function completarTarea(Request $request, $alumno_tarea_id)
    {
        $tarea = AlumnoTarea::find($alumno_tarea_id);
        $exito = 0;

        if (isset($tarea) and ($tarea->estado() == AlumnoTarea::ACTIVA))
        {
            $exito = $tarea->completar();
        }
        return view('tarea', compact('tarea', 'exito'));
    }

}
