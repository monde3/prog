<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\TiempoTarea;
use App\AlumnoTarea;
use App\Tarea;

class GraficaProfesorController extends Controller
{
    /**
     * Devuelve un objeto json con los datos de la gráfica de la tarea de un profesor
     *
     * @param  string  $cod_tarea
     * @return \Illuminate\Http\Response
     */
    public function index($cod_tarea)
    {
        //
        $datosGrafica = collect();
        $tarea = Tarea::find(str_pad($cod_tarea, 10, "0", STR_PAD_LEFT));

        $fechaFin = Carbon::parse($tarea->fecha_fin, 'Europe/Madrid');

        $mesFin = $fechaFin->format('m') + 1;
        $anyoFin = $fechaFin->format('Y');
        if ($mesFin == 13){

            $mesFin = 1;
            $anyoFin++;
        }
        
        $fechaInicio = DB::table('tareas')
                       ->join('alumno_tareas', 'tareas.cod_tarea', '=', 'alumno_tareas.cod_tarea')
                       ->join('tiempo_tareas', 'tiempo_tareas.alumno_tarea_id', '=', 'alumno_tareas.id')
                       ->where('tareas.cod_tarea', str_pad($cod_tarea, 10, "0", STR_PAD_LEFT))
                       ->min('tiempo_tareas.inicio');

        $mesInicio = $mesFin;
        $anyoInicio = $anyoFin;

        if (!is_null($fechaInicio)){
            
            $fechaInicio = Carbon::parse($fechaInicio, 'Europe/Madrid');

            $mesInicio = $fechaInicio->format('m') + 0;
            $anyoInicio = $fechaInicio->format('Y');

            $mesActual = $mesInicio;
            $anyoActual = $anyoInicio;

            while ($mesActual != $mesFin or $anyoActual != $anyoFin){

                $tiempoParcial = 0;
                foreach ($tarea->alumnos as $alumnosTareas) {
                    foreach ($alumnosTareas->tiemposTareas as $tiempoTarea) {
                        $tiempoParcial = $tiempoParcial + $tiempoTarea->tiempoParcialMes($mesActual, $anyoActual);
                    }
                }

                $etiqueta = '';

                if ($mesActual == 1){
                    $etiqueta = 'Ene. ';
                } elseif ($mesActual == 2) {
                    $etiqueta = 'Feb. ';
                } elseif ($mesActual == 3) {
                    $etiqueta = 'Mar. ';
                } elseif ($mesActual == 4) {
                    $etiqueta = 'Abr. ';
                } elseif ($mesActual == 5) {
                    $etiqueta = 'May. ';
                } elseif ($mesActual == 6) {
                    $etiqueta = 'Jun. ';
                } elseif ($mesActual == 7) {
                    $etiqueta = 'Jul. ';
                } elseif ($mesActual == 8) {
                    $etiqueta = 'Ago. ';
                } elseif ($mesActual == 9) {
                    $etiqueta = 'Sep. ';
                } elseif ($mesActual == 10) {
                    $etiqueta = 'Oct. ';
                } elseif ($mesActual == 11) {
                    $etiqueta = 'Nov. ';
                } elseif ($mesActual == 12) {
                    $etiqueta = 'Dic. ';
                }
                $etiqueta = $etiqueta . $anyoActual;

                $tiempoParcial = $tiempoParcial/Carbon::SECONDS_PER_MINUTE;
                $tiempoParcial = $tiempoParcial/Carbon::MINUTES_PER_HOUR;
                $tiempoParcial = round($tiempoParcial, 2);

                $datosGrafica->push([$etiqueta, $tiempoParcial]);

                $mesActual++;

                if ($mesActual == 13){

                    $mesActual = 1;
                    $anyoActual++;
                }
            }
        }
        
        
        $numElementos = $datosGrafica->count();

        if ($numElementos < 6){
            $mesActual = $mesInicio;
            $anyoActual = $anyoInicio;
            for ($i=$numElementos; $i<6; $i++){

                $mesActual--;
                
                if ($mesActual == 0){

                    $mesActual = 12;
                    $anyoActual--;
                }

                $etiqueta = '';

                if ($mesActual == 1){
                    $etiqueta = 'Ene. ';
                } elseif ($mesActual == 2) {
                    $etiqueta = 'Feb. ';
                } elseif ($mesActual == 3) {
                    $etiqueta = 'Mar. ';
                } elseif ($mesActual == 4) {
                    $etiqueta = 'Abr. ';
                } elseif ($mesActual == 5) {
                    $etiqueta = 'May. ';
                } elseif ($mesActual == 6) {
                    $etiqueta = 'Jun. ';
                } elseif ($mesActual == 7) {
                    $etiqueta = 'Jul. ';
                } elseif ($mesActual == 8) {
                    $etiqueta = 'Ago. ';
                } elseif ($mesActual == 9) {
                    $etiqueta = 'Sep. ';
                } elseif ($mesActual == 10) {
                    $etiqueta = 'Oct. ';
                } elseif ($mesActual == 11) {
                    $etiqueta = 'Nov. ';
                } elseif ($mesActual == 12) {
                    $etiqueta = 'Dic. ';
                }
                $etiqueta = $etiqueta . $anyoActual;

                $datosGrafica->prepend([$etiqueta, 0]);
            }

        }

        return Response()->json($datosGrafica);
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
    public function edit($id)
    {
        //
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
