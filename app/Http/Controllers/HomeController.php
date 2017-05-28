<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.2/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\AlumnoTarea;
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
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if  ($request->user()->rol == 'alumno'){
            //Obtenemos las próximas cinco tareas ordenadas por el tiempo que f
            $misProximasTareas = AlumnoTarea::where('user_id', $request->user()->id)->get();

            $misProximasTareas = $misProximasTareas->reject(function ($tareaAlumno) {
                return Carbon::parse($tareaAlumno->tarea->fecha_fin) < Carbon::now('Europe/Madrid');
            })->sortBy( function ( $tareaAlumno ){
                return $tareaAlumno->tarea->tiempoRestante();
            })->take(5);
        }

        return view('home', compact('misProximasTareas'));
    }
}