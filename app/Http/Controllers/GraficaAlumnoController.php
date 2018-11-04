<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\TiempoTarea;
use App\AlumnoTarea;

class GraficaAlumnoController extends Controller
{
    /**
     * Devuelve un objeto json con los datos de la gráfica de la tarea de un alumno
     *
     * @param  int  $alumno_tarea_id
     * @return \Illuminate\Http\Response
     */
    public function index($alumno_tarea_id)
    {
        //
        $tarea = AlumnoTarea::find($alumno_tarea_id);
        $misTiemposTareas = $tarea->tiemposTareas;
        $fechaInicio = Carbon::parse($misTiemposTareas->min('inicio'), 'Europe/Madrid');
        $fechaFin = Carbon::parse($tarea->tarea->fecha_fin, 'Europe/Madrid');

        $mesInicio = $fechaInicio->format('m') + 0;
        $anyoInicio = $fechaInicio->format('Y');

        $mesFin = $fechaFin->format('m') + 1;
        $anyoFin = $fechaFin->format('Y');
        if ($mesFin == 13){

            $mesFin = 1;
            $anyoFin++;
        }

        $mesActual = $mesInicio;
        $anyoActual = $anyoInicio;

        $datosGrafica = collect();


        while ($mesActual != $mesFin or $anyoActual != $anyoFin){

            $tiempoParcial = 0;
            foreach ($misTiemposTareas as $tiempoTarea) {
                $tiempoParcial = $tiempoParcial + $tiempoTarea->tiempoParcialMes($mesActual, $anyoActual);
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
}
