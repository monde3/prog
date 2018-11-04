<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\AlumnoTarea;
use App\Tarea;
use App\Asignatura;
use Carbon\Carbon;
use Validator;

class ServiciosController extends Controller
{
    /**
    * Servicio REST que devuelve el tiempo de un alumno en una tarea en un objeto json
    *
    * @param  string  $dni
    * @param  string  $cod_tarea
    * @return \Illuminate\Http\Response
    */
    public function tiempoAlumnoTarea($dni, $cod_tarea){

    	$usuario = User::where('dni', $dni)->first();

    	if (!isset($usuario)){
    		return Response()->json(0);	
    	}

    	$tarea = AlumnoTarea::where('user_id', $usuario->id)->where('cod_tarea', str_pad($cod_tarea, 10, "0", STR_PAD_LEFT))->first();

		if (!isset($tarea)){
    		return Response()->json(0);	
    	}

        return Response()->json($tarea->tiempoTotal());
    }

    /**
    * Servicio REST que crea una tarea a partir de un objeto json
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function crearTarea(request $request){
        
        if (!is_array($request->all())) {
            return ['error' => 'La petición tiene que ser un array'];
        }

        $messages = [
            'required'    => 'El campo :attribute es obligatorio.',
            'size'        => 'El tamaño del campo :attribute debe ser excatamente :size caracteres.',
            'max'         => 'El tamaño del campo :attribute debe no debe ser mayor de :max caracteres.',
            'date_format' => 'El campo :attribute no cumple el formato d-m-Y H:i',
        ];

        $rules = [
            'CodigoTarea'           => 'required|size:10',
            'TituloTarea'           => 'required|max:100',
            'DescripcionTarea'      => 'required|max:255',
            'CursoAcademico'        => 'required|size:4',
            'TiempoEstimado'        => 'required',
            'FechaFin'              => 'required|date_format:"d/m/Y H:i"',
            'DniPropietario'        => 'required|size:8',
            'NombrePropietario'     => 'required|max:50',
            'ApellidosPropietario'  => 'required|max:100',
            'EmailPropietario'      => 'required|max:50|email',
            'CodigoAsignatura'      => 'required|size:4',
            'DescripcionAsignatura' => 'required|max:150',
            ];

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return [
                    'created' => false,
                    'errors'  => $validator->errors()->all()
                ];
            }

            $tarea = Tarea::find($request->input('CodigoTarea'));

            if (isset($tarea)){
                //La tarea ya existe
                return [
                    'created' => false,
                    'errors'  => 'La tarea ya esta registrada en la base de datos.'
                ];
            }


            //Obtenemos o añadimos el usuario
            $usuario = User::where('dni', $request->input('DniPropietario'))->first();
            $usuarioEmail = User::where('email',  $request->input('EmailPropietario'))->first();

            if (isset($usuario)){
                //Hemos recuperado un usuario con el dni indicado
                if (isset($usuarioEmail)){
                    if ($usuario->id != $usuarioEmail->id){
                        //El email está asociado a otro usuario.
                        return [
                            'created' => false,
                            'errors'  => 'El email esta asociado a otro usuario.'
                        ];
                    }
                }

            } else{

                //No hay ninún usuario registrado con el DNI indicado
                if (isset($usuarioEmail)){
                    //La tarea ya existe
                    return [
                        'created' => false,
                        'errors'  => 'El email esta asociado a otro usuario.'
                    ];
                }

                $usuario = new User;

                $usuario->dni = $request->input('DniPropietario');
                $usuario->email = $request->input('EmailPropietario');
                $usuario->nombre = $request->input('NombrePropietario');
                $usuario->apellidos = $request->input('ApellidosPropietario');
                $usuario->password = bcrypt('123456');
                $usuario->rol = 'profesor';
                $usuario->activo = false;
                $usuario->save();
            
            }

            //Obtenemos o añadimos la asignatura
            $asignatura = Asignatura::find($request->input('CodigoAsignatura'));

            if (!isset($asignatura)){
                $asignatura = new Asignatura;

                $asignatura->cod_asi = $request->input('CodigoAsignatura');
                $asignatura->des_asi = $request->input('DescripcionAsignatura');
                $asignatura->save();
            }

            //Añadimos la tarea
            $tarea = new Tarea;

            $tarea->cod_tarea = $request->input('CodigoTarea');
            $tarea->titulo = $request->input('TituloTarea');
            $tarea->des_tarea = $request->input('TituloTarea');
            $tarea->curso_academico = $request->input('CursoAcademico');
            $tarea->tiempo_estimado = floatval($request->input('TiempoEstimado'));
            $tarea->fecha_fin = Carbon::createFromFormat ('d/m/Y H:i', $request->input('FechaFin'), 'Europe/Madrid');

            $tarea->propietario_id = $usuario->id;
            $tarea->cod_asi = str_pad($asignatura->cod_asi, 4, "0", STR_PAD_LEFT);
            
            $tarea->save();

            // Si el validador pasa, almacenamos el usuario
            return ['created' => true];
        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            \Log::info('Error creando la tarea: '.$e);
            return \Response::json(['created' => false], 500);
        }


        return ['created' => true];
    }

    /**
    * Servicio REST que añade a un alumno a una tarea a partir de un objeto json
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function addAlumno(request $request){
        
        if (!is_array($request->all())) {
            return ['error' => 'La petición tiene que ser un array'];
        }

        $messages = [
            'required'    => 'El campo :attribute es obligatorio.',
            'size'        => 'El tamaño del campo :attribute debe ser excatamente :size caracteres.',
            'max'         => 'El tamaño del campo :attribute debe no debe ser mayor de :max caracteres.',
            'date_format' => 'El campo :attribute no cumple el formato d-m-Y H:i',
        ];

        $rules = [
            'CodigoTarea'      => 'required|size:10',
            'DniAlumno'        => 'required|size:8',
            'NombreAlumno'     => 'required|max:50',
            'ApellidosAlumno'  => 'required|max:100',
            'EmailAlumno'      => 'required|max:50|email',
            ];

        try {
            // Ejecutamos el validador y en caso de que falle devolvemos la respuesta
            // con los errores
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return [
                    'created' => false,
                    'errors'  => $validator->errors()->all()
                ];
            }

            $tarea = Tarea::find($request->input('CodigoTarea'));

            if (!isset($tarea)){
                //La tarea no existe
                return [
                    'created' => false,
                    'errors'  => 'La tarea no esta registrada en la base de datos.'
                ];
            }


            //Obtenemos o añadimos el usuario
            $usuario = User::where('dni', $request->input('DniAlumno'))->first();
            $usuarioEmail = User::where('email',  $request->input('EmailAlumno'))->first();

            if (isset($usuario)){
                //Hemos recuperado un usuario con el dni indicado
                if (isset($usuarioEmail)){
                    if ($usuario->id != $usuarioEmail->id){
                        //El email está asociado a otro usuario.
                        return [
                            'created' => false,
                            'errors'  => 'El email esta asociado a otro usuario.'
                        ];
                    }
                }

            } else{

                //No hay ninún usuario registrado con el DNI indicado
                if (isset($usuarioEmail)){
                    //La tarea ya existe
                    return [
                        'created' => false,
                        'errors'  => 'El email esta asociado a otro usuario.'
                    ];
                }

                $usuario = new User;

                $usuario->dni = $request->input('DniAlumno');
                $usuario->email = $request->input('EmailAlumno');
                $usuario->nombre = $request->input('NombreAlumno');
                $usuario->apellidos = $request->input('ApellidosAlumno');
                $usuario->password = bcrypt('123456');
                $usuario->rol = 'alumno';
                $usuario->activo = false;
                $usuario->save();
            }

            //Añadimos la tarea
            $alumnoTarea = new AlumnoTarea;

            $alumnoTarea->cod_tarea = str_pad($tarea->cod_tarea, 10, "0", STR_PAD_LEFT);
            $alumnoTarea->user_id = $usuario->id;

            
            $alumnoTarea->save();

            // Si el validador pasa, almacenamos el usuario
            return ['created' => true];
        } catch (Exception $e) {
            // Si algo sale mal devolvemos un error.
            \Log::info('Error añadiendo el alumno: '.$e);
            return \Response::json(['created' => false], 500);
        }


        return ['created' => true];
    } 
}
