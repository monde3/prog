<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\AlumnoTarea;
use App\Tarea;
use App\TiempoTarea;
use App\User;
use App\Avatar;
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
    public function gestionPomodoro(request $request, $alumno_tarea_id, $fase_actual) {
        $value = '';
        $alumnoTarea = AlumnoTarea::find($alumno_tarea_id);
        $avatar = Avatar::find($alumnoTarea->user_id);
        $estado = $alumnoTarea->estado();
        $fechaFinTarea = Carbon::parse($alumnoTarea->tarea->fecha_fin, 'Europe/Madrid');       
        $ahora =  Carbon::parse('now', 'Europe/Madrid');

        // DESCANSO->POMODORO
        // Guardamos INICIO de POMODORO en sesión
        // Comprobamos si el tiempo de descanso es mayor que el establecido
        if ($fase_actual == 0) {
            if ($estado === AlumnoTarea::ACTIVA and $fechaFinTarea > $ahora) {
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
                        $avatar->sumarVida(Self::VIDA_SUMAR);
                    }
                }
                $value = 'OK/'.$avatar->vida;
            }
            else{
                $value = 'ERR/'.trans('adminlte_lang::message.tareafinalizada');
            }
        // POMODORO->DESCANSO
        // Guardamos inicio de descanso
        // Comprobamos si el tiempo de pomodoro es el correcto
        } else if ($fase_actual == 1) {
            $request->session()->flash('descanso'.$alumno_tarea_id, Carbon::now('Europe/Madrid'));

            $inicio_pomodoro = Carbon::createFromFormat('Y-m-d H:i:s', $request->session()->get('pomodoro'.$alumno_tarea_id));
            $fin_pomodoro = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now('Europe/Madrid'));

            $diff = $inicio_pomodoro->diff($fin_pomodoro);

            // i = MINUTOS
            //if ($diff->i >= Self::POMODORO){
            if ($diff->s >= Self::POMODORO){
                $avatar->sumarOro(Self::ORO_SUMAR);
            }

            // Cerramos el intervalo de trabajo en la tarea
            $fechaFinTarea = Carbon::parse($alumnoTarea->tarea->fecha_fin, 'Europe/Madrid');

            $tiempo = new TiempoTarea;
            $tiempo->alumno_tarea_id = $alumno_tarea_id;
            $tiempo->inicio = $inicio_pomodoro;

            if ($fechaFinTarea > $fin_pomodoro) {
                $tiempo->fin = $fin_pomodoro;
            } else {
                $tiempo->fin = $fechaFinTarea;
            }

            $tiempo->save();

            $value = 'OK/'.$avatar->oro;
        }
 
        return $value;
    }

}
