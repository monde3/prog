<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tarea;

class TareaProfesorController extends Controller
{
    /**
     * Pantalla del detalle de la tarea de un profesor
     *
     * @param  string $cod_tarea
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($cod_tarea, Request $request)
    {
        if (!$request->user()->activo){
            abort(403);
        }
        
        $tarea = Tarea::find(str_pad($cod_tarea, 10, "0", STR_PAD_LEFT));
        
        return view('tarea', compact('tarea'));
    }
}
