<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tareas = DB::table('tareas')
                    ->join('alumno_tareas', 'tareas.cod_tarea', '=', 'alumno_tareas.cod_tarea')
                    ->join('asignaturas', 'tareas.cod_asi', '=', 'asignaturas.cod_asi')
                    ->leftJoin('tiempo_tareas', function ($join) {
                        $join->on('alumno_tareas.alumno_id', '=', 'tiempo_tareas.alumno_id')
                             ->on('alumno_tareas.cod_tarea', '=', 'tiempo_tareas.cod_tarea')
                             ->on('alumno_tareas.curso_academico', '=', 'tiempo_tareas.curso_academico');
                    })
                    ->where('alumno_tareas.alumno_id', $request->user()->id)
                    ->where('tareas.fecha_fin', '>', Carbon::now())
                    ->select('alumno_tareas.alumno_id',
                             'alumno_tareas.cod_tarea',
                             'alumno_tareas.curso_academico',
                             'asignaturas.des_asi',
                             'tareas.titulo',
                             DB::raw('SUM(tiempo_tareas.inicio - tiempo_tareas.fin) as tiempo')
                            )
                    ->groupBy('alumno_tareas.alumno_id',
                              'alumno_tareas.cod_tarea',
                              'alumno_tareas.curso_academico',
                              'asignaturas.des_asi',
                              'tareas.titulo'
                             )
                    ->get();

        return view('home')->With(['tareas' => $tareas]);
    }
}
