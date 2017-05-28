<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AlumnoTarea;
use App\TiempoTarea;
use Carbon\Carbon;

class TareaAlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($alumno_tarea_id, Request $request)
    {
        //
        $tarea = AlumnoTarea::find($alumno_tarea_id);
        
        return view('tareaalumno', compact('tarea'));
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
    public function store(Request $request)
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

        
        return redirect()->route('tareaalumno', ['alumno_tarea_id' => $alumno_tarea_id]);
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
