<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Imagen;

class AvatarImagenController extends Controller
{
    const ITEMS_PAGINA = 16;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	if($request->user()->rol == 'alumno'){
    		$imagenes_compradas = $request->user()->avatar->avatarImagenes()->paginate(Self::ITEMS_PAGINA);
    		$imagenes_seleccionadas = array(
                $request->user()->avatar->img_activo,
                $request->user()->avatar->img_inactivo,
                $request->user()->avatar->img_herido,
				$request->user()->avatar->img_head,
				$request->user()->avatar->img_body,
				$request->user()->avatar->img_hands,
                $request->user()->avatar->img_feet,
				$request->user()->avatar->img_weapon);
    		return view('editarimagenavatar', compact('imagenes_compradas', 'imagenes_seleccionadas'));
    	}else{
   			return redirect('/home')->with(array('error' => trans('adminlte_lang::message.errornotalumn')));
    	}
    }

    public function cambiarImagenAvatar(Request $request, $imagen_id, $estado)
    {
    	if($request->user()->rol == 'alumno'){
    		$imagen = Imagen::find($imagen_id);
    		if(isset($imagen)){
    			if($imagen->parte == 'avatar'){
    				if($estado == 'activo'){
    					$request->user()->avatar->img_activo = $imagen->id;
	    			}
	    			else if($estado == 'inactivo'){
	    				$request->user()->avatar->img_inactivo = $imagen->id;
	    			}
	    			else if($estado == 'herido'){
	    				$request->user()->avatar->img_herido = $imagen->id;
	    			}
    			}
    			else if($imagen->parte == 'head'){
    				$request->user()->avatar->img_head = $imagen->id;
    			}
    			else if($imagen->parte == 'body'){
    				$request->user()->avatar->img_body = $imagen->id;
    			}
    			else if($imagen->parte == 'hands'){
    				$request->user()->avatar->img_hands = $imagen->id;
    			}
    			else if($imagen->parte == 'feet'){
    				$request->user()->avatar->img_feet = $imagen->id;
    			}
    			else if($imagen->parte == 'weapon'){
    				$request->user()->avatar->img_weapon = $imagen->id;
    			}
    			else{
    				return redirect('/editarImagenAvatar')->with(array('error' => trans('adminlte_lang::message.problems')));
    			}
    			$request->user()->avatar->save();
    		}
    	}else{
   			return redirect('/home')->with(array('error' => trans('adminlte_lang::message.errornotalumn')));
    	}
   		return redirect('/avatar')->with(array('message' => trans('adminlte_lang::message.imagechanged')));
    }

}
