<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TiempoTarea;
use App\AlumnoTarea;
use Carbon\Carbon;
use Validator;

class TiempoTareaController extends Controller
{
    
    /**
     * Pantalla para crear un nuevo tiempo registrado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $tarea = AlumnoTarea::where('id', $id)->where('user_id', $request->user()->id)->first();

        if(!isset($tarea) or !$tarea->alumno->activo){
            abort(403);
        } 

        return view('creartiempotareaalumno', compact('tarea'));
    }

    /**
     * Guarda un nuevo tiempo registrado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $tarea = AlumnoTarea::where('id', $request->input('AlumnoTareaId'))->where('user_id', $request->user()->id)->first();

        if(!isset($tarea) or !$tarea->alumno->activo){
            abort(403);
        }

        $messages = [
            'required'    => 'El campo :attribute es obligatorio.',
            'date_format' => 'El campo :attribute no cumple el formato indicado',
        ];

        $rules = [
            'AlumnoTareaId'       => 'required',
            'FechaInicial'        => 'required|date_format:"d/m/Y"',
            'FechaFinal'          => 'required|date_format:"d/m/Y"',
            'TiempoInicial'       => 'required|date_format:"H:i:s"',
            'TiempoFinal'         => 'required|date_format:"H:i:s"',
            ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->
                   route('crearTiempo', ['id' => $tarea->id])->
                   withErrors($validator);
        }

        $fechaInicial = $request->input('FechaInicial');
        $tiempoInicial = $request->input('TiempoInicial');
        
        $fechaFinal = $request->input('FechaFinal');
        $tiempoFinal = $request->input('TiempoFinal');
        
        $inicio = Carbon::createFromFormat ('d/m/Y H:i:s', $fechaInicial.' '.$tiempoInicial, 'Europe/Madrid');
        $fin = Carbon::createFromFormat ('d/m/Y H:i:s', $fechaFinal.' '.$tiempoFinal, 'Europe/Madrid');

        if ($inicio >= $fin){
            return redirect()->
                   route('crearTiempo', ['id' => $tarea->id])->
                   withErrors(['La fecha final no puede ser menor o igual a la inicial']);
        }

        $fechaFinTarea = Carbon::parse($tarea->tarea->fecha_fin, 'Europe/Madrid');
        if ($fin >=  $fechaFinTarea){
            return redirect()->
                   route('crearTiempo', ['id' => $tarea->id])->
                   withErrors(['La fecha final no puede ser mayor que la fecha final de la tarea']);
        }


        $tiempo = new TiempoTarea;

        $tiempo->alumno_tarea_id = $tarea->id;

        $tiempo->inicio = $inicio;
        $tiempo->fin = $fin;
        $tiempo->save();

        return redirect()->
               route('tareaalumno', ['alumno_tarea_id' => $tiempo->alumno_tarea_id]);
    }


    /**
     * Pantalla para editar un tiempo registrado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(request $request, $id)
    {

        $tiempo = TiempoTarea::find($id); 

        if ($tiempo->alumnoTarea->user_id != $request->user()->id or !$tiempo->alumnoTarea->alumno->activo){
            abort(403);
        }

        return view('editartiempotareaalumno', compact('tiempo'));
    }

    /**
     * Actualiza un tiempo registrado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $tiempo = TiempoTarea::find($id);

        if (!isset($tiempo) or !$tiempo->alumnoTarea->alumno->activo){
            abort(403);
        }

        $messages = [
            'required'    => 'El campo :attribute es obligatorio.',
            'date_format' => 'El campo :attribute no cumple el formato indicado',
        ];

        $rules = [
            'FechaInicial'        => 'required|date_format:"d/m/Y"',
            'FechaFinal'          => 'required|date_format:"d/m/Y"',
            'TiempoInicial'       => 'required|date_format:"H:i:s"',
            'TiempoFinal'         => 'required|date_format:"H:i:s"',
            ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->
                   route('tiempotarea.edit', ['id' => $id])->
                   withErrors($validator);
        }

        

        $fechaInicial = $request->input('FechaInicial');
        $tiempoInicial = $request->input('TiempoInicial');
        
        $fechaFinal = $request->input('FechaFinal');
        $tiempoFinal = $request->input('TiempoFinal');
        
        $inicio = Carbon::createFromFormat ('d/m/Y H:i:s', $fechaInicial.' '.$tiempoInicial, 'Europe/Madrid');
        $fin = Carbon::createFromFormat ('d/m/Y H:i:s', $fechaFinal.' '.$tiempoFinal, 'Europe/Madrid');
        
        if ($inicio >= $fin){
            return redirect()->
                   route('tiempotarea.edit', ['id' => $id])->
                   withErrors(['La fecha final no puede ser menor o igual a la inicial']);
        }

        $fechaFinTarea = Carbon::parse($tiempo->alumnoTarea->tarea->fecha_fin, 'Europe/Madrid');
        if ($fin >=  $fechaFinTarea){
            return redirect()->
                   route('tiempotarea.edit', ['id' => $id])->
                   withErrors(['La fecha final no puede ser mayor que la fecha final de la tarea']);
        }

        $tiempo = TiempoTarea::find($id);

        $tiempo->inicio = $inicio;
        $tiempo->fin = $fin;
        $tiempo->save();

        return redirect()->
               route('tareaalumno', ['alumno_tarea_id' => $tiempo->alumno_tarea_id]);
    }

    /**
     * Devuelve el tiempo del progreso en una tarea formateado
     *
     * @param  int  $tiempo_tarea_id
     * @return string
     */
    public function tiempo($tiempo_tarea_id){
        $tiempo = TiempoTarea::find($tiempo_tarea_id);
        return $tiempo->tiempoParcialFormateado();
    }
}
