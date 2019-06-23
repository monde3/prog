<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symphony\Component\HttpFoundation\Response;

use App\Avatar;
use App\User;
use App\Notificacion;

class AvatarController extends Controller
{
    const MEJORA = 5;
    const COSTE_MEJORA = 10;
    const OK_RESPONSE = 200;

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
        $nivelAvatar = $avatar->nivelAvatar();
        //Actualizamos el estado
        $avatar->estadoActual();

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

        if($avatar->oro >= Self::COSTE_MEJORA){
            if($valor == 'head'){
                $avatar->sumarHead(Self::MEJORA);
                $valor_avatar = $avatar->head;
            }else if($valor == 'body'){
                $avatar->sumarBody(Self::MEJORA);
                $valor_avatar = $avatar->body;
            }else if($valor == 'hands'){
                $avatar->sumarHands(Self::MEJORA);
                $valor_avatar = $avatar->hands;
            }else if($valor == 'feet'){
                $avatar->sumarFeet(Self::MEJORA);
                $valor_avatar = $avatar->feet;
            }else if($valor == 'weapon'){
                $avatar->sumarWeapon(Self::MEJORA);
                $valor_avatar = $avatar->weapon;
            }
            $avatar->restarOro(Self::COSTE_MEJORA);
            $value = $avatar->oro."/".$valor_avatar;
        }else{
            $value = "error";
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

        // Recuperamos la variable de sesión flash
        $oponente_id = $request->session()->pull('combateOponente');
        // Nos aseguramos de que la variable se ha eliminado
        $request->session()->forget('combateOponente');
        $request->session()->save();

        $avatares = Avatar::where('estado', '=', 'activo')
                    ->where('user_id', '!=', $request->user()->id)
                    ->where(DB::raw('FLOOR(SQRT(exp DIV '.Avatar::FACTOR_SUBIDA_NIVEL.'))'),
                            '=', $avatar_usuario->nivelAvatar())
                    ->get();

        if (isset($oponente_id) && ($oponente_id > 0)){
            $resultado = $request->session()->pull('combateResultado');
            $request->session()->forget('combateResultado');
            $request->session()->save();
            $oponente = Avatar::where('user_id', '=', $oponente_id)->firstOrFail();
            return view('luchar', compact('avatares', 'oponente', 'resultado'));
        } else
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
        try {
            $avatar = Avatar::where('user_id', '=', $request->user()->id)->firstOrFail();
            $oponente = Avatar::where('user_id', '=', $opponent_id)->firstOrFail();

            if ($avatar->vida >= 20 and $avatar->estado = 'activo' and
                    $oponente->vida >= 20 and $oponente->estado = 'activo'){
                $vida_antes = $avatar->vida;
                $vida_antes_oponente = $oponente->vida;
                $victoria = $avatar->lucha($oponente);

                $value = ($victoria ? "vic" : "der")."\\".$avatar->oro."\\".$avatar->vida."\\".($vida_antes-$avatar->vida)."\\".($avatar->alumno->nombre)."\\".($oponente->alumno->nombre);

                // Creamos la notificación para el oponente
                $notificacion = new Notificacion;
                $notificacion->user_id = $oponente->alumno->id;
                $notificacion->oponente_id = $avatar->alumno->id;
                $notificacion->texto = $avatar->alumno->nombre." ".trans('adminlte_lang::message.foughtwithyou');
                $notificacion->resultado = ($victoria 
                    ? "KO"."%".trans('adminlte_lang::message.youlost')." "
                    : "OK"."%".trans('adminlte_lang::message.youwon')." ".Avatar::ORO_VICTORIA." puntos de ".trans('adminlte_lang::message.gold')." y perdido ")
                    .($vida_antes_oponente-$oponente->vida)." puntos de ".trans('adminlte_lang::message.life')
                    ."%".($oponente->alumno->nombre)."%".($avatar->alumno->nombre);
                $notificacion->save();
            } else {
                if($avatar->vida < 20 or $avatar->estado != 'activo')
                    $value = "err\\".trans('adminlte_lang::message.fighterror');
                else
                    $value = "err\\".trans('adminlte_lang::message.fightinactiveoponent');
            }
            return $value;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            return var_dump($e);
        }
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
        return Response($file, Self::OK_RESPONSE);
    }
}