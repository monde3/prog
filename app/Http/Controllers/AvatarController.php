<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symphony\Component\HttpFoundation\Response;

use App\Avatar;
use App\User;

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
        $nivelAvatar = floor($avatar->exp / Avatar::PUNTOS_POR_NIVEL);

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

        if($avatar->oro >= 5){

            if($valor == 'head'){
                $avatar->sumarHead(5);
                $valor_avatar = $avatar->head;
            }else if($valor == 'body'){
                $avatar->sumarBody(5);
                $valor_avatar = $avatar->body;
            }else if($valor == 'hands'){
                $avatar->sumarHands(5);
                $valor_avatar = $avatar->hands;
            }else if($valor == 'feet'){
                $avatar->sumarFoot(5);
                $valor_avatar = $avatar->feet;
            }else if($valor == 'weapon'){
                $avatar->sumarWeapon(5);
                $valor_avatar = $avatar->weapon;
            }

            $avatar->restarOro(5);
            
            $value = $avatar->oro."/".$valor_avatar;
        }else{
            $value = "error/No tiene oro suficiente";
        }
        return $value;
    }

    /**
     * Busca los avatares en estado activo de los alumnos
     * que estén en el mismo nivel
     *
     * @param
     * @return collection - avatares
     */
    public function luchar(request $request) {
        $avatar_usuario = Avatar::where('user_id', '=', $request->user()->id)->firstOrFail();

        $avatares = Avatar::where('estado', '=', 'activo')
                    ->where('user_id', '!=', $request->user()->id)
                    ->where(DB::raw('exp DIV '.Avatar::PUNTOS_POR_NIVEL), '=', floor($avatar_usuario->exp/Avatar::PUNTOS_POR_NIVEL))
                    ->get();
                    
        return view('luchar', compact('avatares'));
    }

    /**
     * Lleva a cabo la lucha entre el usuario logueado y el elegido por éste de forma aleatoria
     * en función a la puntuación que tenga.
     *
     * sumo las cantidades de ambos usuarios
     * luego genero un número aleatorio entre 0 y 32767
     * le asigno la parte proporcional de ese rango en función a su puntuación
     * si sale un número de tu rango, has ganado
     *
     * @param $opponent_id - ID del jugador contra el que se lucha
     * @return resultado - cadena:
     *            victoria/derrota
     *            oro resultante
     *            vida resultante
     *            vida perdida
     */
    public function lucharContra(request $request, $opponent_id) {
        $avatar = Avatar::where('user_id', '=', $request->user()->id)->firstOrFail();
        $oponente = Avatar::where('user_id', '=', $opponent_id)->firstOrFail();

        if ($avatar->vida >= 20 and $avatar->estado = 'activo'){
            $vida_antes = $avatar->vida;
            $victoria = $avatar->lucha($oponente);
            $value = ($victoria ? "vic\\" : "der\\").$avatar->oro."\\".$avatar->vida."\\".($vida_antes-$avatar->vida);
        } else  {
            $value = "err\\".trans('adminlte_lang::message.fighterror');
        }
        
        return $value;
    }

    /**
     * Cambia el estado del avatar
     *
     * @param $opponent_id - ID del jugador contra el que se lucha
     * @return resultado
     */
    public function cambiarEstadoAvatar(request $request, $avatar_user_id, $estado) {
        $avatar = Avatar::where('user_id', '=', $avatar_user_id)->firstOrFail();
        if($avatar->vida >= 20 and $estado != 'herido'){
            $avatar->cambiarEstado($estado);
            $value = "ok\\".$estado."\\".route('imagenAvatar', ['user_id' => $avatar->user_id, 'parte' => 'avatar']);
        }else{
            $value = "err\\".trans('adminlte_lang::message.notenaughlifestate');
        }
        return $value;
    }

    public function imagenAvatar(request $request, $user_id, $parte){
        $avatar = User::find($user_id)->avatar;
        $file = Storage::disk('images')->get($avatar->imagePath($parte));
        return Response($file, 200);
    }
}