<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.2/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\AlumnoTarea;
use App\Tarea;
use App\User;
use App\Avatar;
use Carbon\Carbon;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Pantalla de inicio
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario->activo){
            abort(403);
        } elseif ($usuario->rol == 'alumno'){
            //Obtenemos las próximas cinco tareas ordenadas por el tiempo que finalizan
            $misProximasTareas = AlumnoTarea::where('user_id', $usuario->id)->get();

            $misProximasTareas = $misProximasTareas->reject(function ($tareaAlumno) {
                return Carbon::parse($tareaAlumno->tarea->fecha_fin) < Carbon::now('Europe/Madrid');
            })->sortBy( function ( $tareaAlumno ){
                return $tareaAlumno->tarea->tiempoRestante();
            })->map(function ($tareaAlumno){
                return $tareaAlumno->tarea;
            })->take(5);

            // (amondejar)
            // Obtenemos información del avatar
            $avatar = Avatar::where('user_id', $usuario->id)->get()->first();

            // (amondejar)
            // Calculamos el nivel en el que nos encontramos
            $nivelAvatar = $avatar->nivelAvatar();

        } elseif  ($usuario->rol == 'profesor'){
            //Obtenemos las próximas cinco tareas ordenadas por el tiempo que finalizan
            $misProximasTareas = Tarea::where('propietario_id', $usuario->id)->get();
            
            $misProximasTareas = $misProximasTareas->reject(function ($tarea) {
                return Carbon::parse($tarea->fecha_fin) < Carbon::now('Europe/Madrid');
            })->sortBy( function ( $tarea ){
                return $tarea->tiempoRestante();
            })->take(5);
        }

        return view('home', compact('misProximasTareas', 'nivelAvatar', 'avatar'));
    }
}