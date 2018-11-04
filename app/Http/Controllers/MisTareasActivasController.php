<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\AlumnoTarea;
use App\Tarea;
use App\TiempoTarea;
use App\User;
use Carbon\Carbon;

class MisTareasActivasController extends Controller {

    const POMODORO = 5;
    const DESCANSO = 5;
    const ORO_SUMAR = 5;
    const VIDA_SUMAR = 5;

    /**
     * Pantalla de las tareas activas de un alumno/profesor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //
        if (!$request->user()->activo) {
            abort(403);
        } elseif ($request->user()->rol == 'alumno') {
            $tareas = AlumnoTarea::where('user_id', $request->user()->id)->get();

            $tareas = $tareas->reject(function ($tareaAlumno) {
                        return Carbon::parse($tareaAlumno->tarea->fecha_fin, 'Europe/Madrid') < Carbon::now('Europe/Madrid');
                    })->sortBy(function ( $tareaAlumno ) {
                return $tareaAlumno->tarea->tiempoRestante();
            });
        } elseif ($request->user()->rol == 'profesor') {
            $tareas = Tarea::where('propietario_id', $request->user()->id)->get();

            $tareas = $tareas->reject(function ($tarea) {
                        return Carbon::parse($tarea->fecha_fin) < Carbon::now('Europe/Madrid');
                    })->sortBy(function ( $tarea ) {
                return $tarea->tiempoRestante();
            });
        }

        return view('mistareas', compact('tareas'));
    }

    /**
     * Start/Stop del cronómetro de la tarea de un alumno
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $alumno_tarea_id
     * @return \Illuminate\Http\Response
     */
    public function edit(request $request, $alumno_tarea_id) {
        //
        $miTarea = AlumnoTarea::find($alumno_tarea_id);

        if ($miTarea->user_id != $request->user()->id or ! $miTarea->alumno->activo) {
            abort(403);
        }

        $estado = $miTarea->estado();
        $fechaFinTarea = Carbon::parse($miTarea->tarea->fecha_fin, 'Europe/Madrid');
        $ahora = Carbon::now('Europe/Madrid');

        if ($estado === AlumnoTarea::ACTIVA and $fechaFinTarea > $ahora) {
            $tiempo = new TiempoTarea;
            $tiempo->alumno_tarea_id = $alumno_tarea_id;
            $tiempo->inicio = Carbon::now('Europe/Madrid');
            $tiempo->save();
        } elseif ($estado === AlumnoTarea::EN_PROGRESO) {
            $miTiempoActivo = TiempoTarea::where('alumno_tarea_id', $miTarea->id)->whereNull('fin')->first();

            if ($fechaFinTarea > $ahora) {
                $miTiempoActivo->fin = $ahora;
            } else {
                $miTiempoActivo->fin = $fechaFinTarea;
            }

            $miTiempoActivo->save();
        }


        return Redirect::back();
    }

    /**
     * Devuelve el tiempo de un alumno en una tarea formateado
     *
     * @param  int  $alumno_tarea_id
     * @return string
     */
    public function tiempo($alumno_tarea_id) {
        $tarea = AlumnoTarea::find($alumno_tarea_id);
        return $tarea->tiempoTotalFormateado();
    }

    /**
     * Gestiona el tiempo transcurrido de cualquiera de las fases del pomodoro (trabajo(0)/descanso(1))
     * Guarda el inicio de una fase en sesión hasta el próximo
     *
     * @param int  $alumno_tarea_id
     *        int  $fase_actual
     * @return string
     */
    public function comenzarPomodoro(request $request, $alumno_tarea_id, $fase_actual) {
        $value = '';
        $tarea = AlumnoTarea::find($alumno_tarea_id);
        $usuario = User::find($tarea->user_id);

        // DESCANSO->POMODORO
        // Guardamos inicio de pomodoro
        // Comprobamos si el tiempo de descanso es mayor que el establecido
        if ($fase_actual == 0) {
            // Guardamos en variable de sesión flash
            $request->session()->flash('pomodoro'.$alumno_tarea_id, Carbon::now('Europe/Madrid'));

            // Recuperamos la variable de sesión flash
            $descanso = $request->session()->get('descanso'.$alumno_tarea_id);
            // La primera llamada no se habrá registrado el descanso
            // solo se hará la inserción del instante inicial del pomodoro
            if (!empty($descanso)) {
                // Para evitar problemas con la zona horaria creamos los dos instantes
                // con la misma función pasando el formato
                $inicio = Carbon::createFromFormat('Y-m-d H:i:s', $descanso);
                $fin = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now('Europe/Madrid'));

                $diff = $inicio->diff($fin);

                // Comprobamos los minutos (i) 
                //if ($diff->i >= Self::DESCANSO){
                if ($diff->s >= Self::DESCANSO){
                    $usuario->sumarVida(Self::VIDA_SUMAR);
                }
            }
            $value = $usuario->vida;
                
        // POMODORO->DESCANSO
        // Guardamos inicio de descanso
        // Comprobamos si el tiempo de pomodoro es el correcto
        } else if ($fase_actual == 1) {
            $request->session()->flash('descanso'.$alumno_tarea_id, Carbon::now('Europe/Madrid'));

            $inicio = Carbon::createFromFormat('Y-m-d H:i:s', $request->session()->get('pomodoro'.$alumno_tarea_id));
            $fin = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now('Europe/Madrid'));

            $diff = $inicio->diff($fin);

            //if ($diff->i >= Self::POMODORO){
            if ($diff->s >= Self::POMODORO){
                $usuario->sumarOro(Self::ORO_SUMAR);
            }
            $value = $usuario->oro;
        }

        return $value;
    }

}
