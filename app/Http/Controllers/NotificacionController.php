<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notificacion;

class NotificacionController extends Controller
{
    
    public function tratarNotificacion(Request $request, $notificacion_id)
    {
		$notificacion = Notificacion::find($notificacion_id);

		if(isset($notificacion) && !is_null($notificacion)){
			$notificacion->activa = false;
			$notificacion->save();
			if(!is_null($notificacion->alumno_tarea_id)){
				$response = redirect(
					route('tareaalumno',['alumno_tarea_id' => $notificacion->alumno_tarea_id]));
			} else if(!is_null($notificacion->oponente_id)){
	            // Guardamos en variable de sesión flash
	            $request->session()->put('combateOponente', $notificacion->oponente_id);
	            $request->session()->put('combateResultado', $notificacion->resultado);
				$response = redirect(route('luchar'));
			}
		}
		else {
			$response = redirect('home')->with(array('error' => trans('adminlte_lang::message.errornotificationid')));
		}

		return $response;
    }
}
