<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symphony\Component\HttpFoundation\Response;

use App\Imagen;
use App\AvatarImagen;

class TiendaController extends Controller
{
    const ITEMS_PAGINA = 16;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$rol_usuario = $request->user()->rol;
        $imagenes_compradas = null;
    	if($rol_usuario == 'alumno'){
    		$imagenes_compradas = $request->user()->avatar->avatarImagenes;
    	}
   		$imagenes = DB::table('imagenes')->orderby('parte')->paginate(Self::ITEMS_PAGINA);
    	return view('tienda', compact('imagenes','imagenes_compradas'));
    }

    /**
     * Guarda una nueva imagen al catálogo de la tienda disponible
     * para los usuarios con rol 'alumno'
     * Se guardarán en la carpeta /storage/app/images/
     *
     * @param 
     * @return 
     */
    public function subirSkin(Request $request){
        //Reglas de validación
        $rules = [
            'parte' => 'required',
            'precio' => 'required',
            'imagen' => 'required|image|dimensions:max_width=1024,max_height=1024'
        ];

        $customMessages = [
            'required' => trans('adminlte_lang::message.requiredvalidationmessage'),
            'digits' => trans('adminlte_lang::message.digitsvalidationmessage'),
            'image' => trans('adminlte_lang::message.imagevalidationmessage'),
            'dimensions' => trans('adminlte_lang::message.dimensionsvalidationmessage')
        ];
        // Nombre de los atributos
        $attributes = [
            'parte' => lcfirst(trans('adminlte_lang::message.part')),
            'precio' => lcfirst(trans('adminlte_lang::message.price')),
            'imagen' => lcfirst(trans('adminlte_lang::message.image'))
        ];

        // Validar formulario
        $validated_data = $this->validate($request, $rules, $customMessages, $attributes);
        $error = null;
        $imagen = new Imagen();

        $imagen->parte = $request->get('parte');
        $imagen->precio = $request->get('precio');

        $archivo_imagen = $request->file('imagen');
        if($archivo_imagen){
            try {
                //Obtenemos el ID que formará parte del nombre del archivo
                $imagen->save();
                $imagen->filename = $imagen->id.'.'.$archivo_imagen->getClientOriginalExtension();
                $imagen->save();
            } catch (Exception $e) {
                $error = trans('adminlte_lang::message.errorsavingimagedb');
            }
            try {
                Storage::disk('images')->put($imagen->filename, \File::get($archivo_imagen));
            } catch (Exception $e) {
                $imagen->delete();
                $error = trans('adminlte_lang::message.errorsavingimagedisk');
            }
        }
        if($error){
            return redirect()->route('tienda')->with(array('error' => $error));
        }

        return redirect()->route('tienda')->with(array(
            'message' => trans('adminlte_lang::message.imageloadok')
            ));
    }

    /**
     * Edita los parámetros de un skin
     *
     * @param 
     * @return 
     */
    public function editarSkin(Request $request, $imagen_id){
        //Reglas de validación
        $rules = [
            'parte_edicion' => 'required',
            'precio_edicion' => 'required'
        ];

        // Mensajes de validación
        $customMessages = [
            'required' => trans('adminlte_lang::message.requiredvalidationmessage'),
            'digits' => trans('adminlte_lang::message.digitsvalidationmessage')
        ];
        
        // Nombre de los atributos
        $attributes = [
            'parte_edicion' => lcfirst(trans('adminlte_lang::message.part')),
            'precio_edicion' => lcfirst(trans('adminlte_lang::message.price'))
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages, $attributes);

        if ($validator->fails())
        {
            return redirect()
            ->route('tienda')
            ->withErrors($validator, 'editionerrors')
            ->with(array(
                'id' => $imagen_id,
                'parte' => $request->get('parte_edicion'),
                'precio' => $request->get('precio_edicion')
            ));
        }

        $error = null;
        $imagen = Imagen::find($imagen_id);

        $imagen->parte = $request->get('parte_edicion');
        $imagen->precio = $request->get('precio_edicion');
        
        try {
            $imagen->save();
        } catch (Exception $e) {
            $error = trans('adminlte_lang::message.errorsavingimagedisk');
        }
        if($error){
            return redirect()->route('tienda')->with(array('error' => $error));
        }
        return redirect()->route('tienda')->with(array(
            'message' => trans('adminlte_lang::message.imageloadok')
            ));
    }

    public function getImage($filename){
        $file = Storage::disk('images')->get($filename);
        return Response($file, 200);
    }

    public function getImageById($imagen_id){
        $imagen = Imagen::find($imagen_id);
        $file = Storage::disk('images')->get($imagen->filename);
        return Response($file, 200);
    }
 
    public function comprarImagen(Request $request, $imagen_id){
        $imagen = Imagen::find($imagen_id);
        $avatar = $request->user()->avatar;

        if (!$avatar->restarOro($imagen->precio)){
            return redirect()->route('tienda')->with(array(
                'error' => trans('adminlte_lang::message.notenoughgold').'. '.trans('adminlte_lang::message.getgoldmessage')
                ));
        }
        
        $avatar_imagen = new AvatarImagen();
        $avatar_imagen->avatar_id = $avatar->id;
        $avatar_imagen->imagen_id = $imagen->id;
        $avatar_imagen->save();

        return redirect()->route('tienda')->with(array(
            'message' => trans('adminlte_lang::message.imageboughtok')
            ));
    }

    public function eliminarImagen(request $request, $imagen_id){
        $imagen = Imagen::find($imagen_id);
        $avatar_imagenes = AvatarImagen::where('imagen_id','=',$imagen_id);
        if($avatar_imagenes->count() > 0){
            return redirect()->route('tienda')->with(array(
                'error' => trans('adminlte_lang::message.errordeleteimageboughtbyuser')
                ));
        }
        $imagen->delete();
        Storage::disk('images')->delete($imagen->filename);
        
        return redirect()->route('tienda')->with(array(
            'message' => trans('adminlte_lang::message.imagedeleted')
            ));
    }
}
