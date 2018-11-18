<?php

namespace App\Http\Controllers;

use App\Avatar;
use App\User;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->activo){
            abort(403);
        }
        
        $avatar = Avatar::where('user_id', '=', $request->user()->id)->firstOrFail();

        //Calculamos el nivel en el que nos encontramos (amondejar)
        $nivelAvatar = floor($request->user()->exp / User::PUNTOS_POR_NIVEL);

        return view('avatar', compact('avatar', 'nivelAvatar'));
    }

    /**
     * Gestiona el tiempo transcurrido de cualquiera de las fases del pomodoro (trabajo(0)/descanso(1))
     * Guarda el inicio de una fase en sesión hasta el próximo
     *
     * @param int  $alumno_tarea_id
     *        int  $fase_actual
     * @return string
     */
    public function aumentarNivelAvatar(request $request, $avatar_user_id, $valor) {
        $valor_avatar = '';
        $avatar = Avatar::where('user_id', '=', $avatar_user_id)->firstOrFail();
        $usuario = User::find($avatar_user_id);

        if($usuario->oro >= 5){

            if($valor == 'head'){
                $avatar->sumarHead(5);
                $valor_avatar = $avatar->head;
            }else if($valor == 'body'){
                $avatar->sumarBody(5);
                $valor_avatar = $avatar->body;
            }else if($valor == 'hands'){
                $avatar->sumarHands(5);
                $valor_avatar = $avatar->hands;
            }else if($valor == 'foot'){
                $avatar->sumarFoot(5);
                $valor_avatar = $avatar->foot;
            }else if($valor == 'weapon'){
                $avatar->sumarWeapon(5);
                $valor_avatar = $avatar->weapon;
            }

            $usuario->restarOro(5);
            
            $value = $usuario->oro."/".$valor_avatar;
        }else{
            $value = "error/No tiene oro suficiente";
        }
        return $value;

    }
    
}
