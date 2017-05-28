<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AlumnoTarea;
use App\TiempoTarea;
use Carbon\Carbon;

class MisTareasActivasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $tareas = AlumnoTarea::where('user_id', $request->user()->id)->get();
        
        $tareas = $tareas->reject(function ($tareaAlumno) {
            return Carbon::parse($tareaAlumno->tarea->fecha_fin) < Carbon::now('Europe/Madrid');
        })->sortBy( function ( $tareaAlumno ){
            return $tareaAlumno->tarea->tiempoRestante();
        });
        
        return view('mistareasalumno', compact('tareas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($alumno_tarea_id)
    {
        //
        $miTarea = AlumnoTarea::find($alumno_tarea_id);
        $accion = $miTarea->accion();
        $fechaFinTarea = Carbon::parse($miTarea->tarea->fecha_fin);
        $ahora = Carbon::now('Europe/Madrid');
        
        if ($accion==="Iniciar" and $fechaFinTarea > $ahora){
            $tiempo = new TiempoTarea;
            $tiempo->alumno_tarea_id = $alumno_tarea_id;
            $tiempo->inicio = Carbon::now('Europe/Madrid');
            $tiempo->save();
        }elseif ($accion==="Parar") {
            $miTiempoActivo = TiempoTarea::where('alumno_tarea_id', $miTarea->id)->whereNull('fin')->first();
            
            if ($fechaFinTarea > $ahora){
                $miTiempoActivo->fin = $ahora;
            }else{
                 $miTiempoActivo->fin = $fechaFinTarea;
            }
            
            $miTiempoActivo->save();
        }

        
        return redirect('mistareasalumno');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
