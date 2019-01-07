<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Pantalla de administrar usuarios
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->user()->rol!='administrador'){
            abort (403);
        }

        $usuarios = User::orderBy('apellidos', 'asc')->orderBy('nombre', 'asc')->paginate(10); 

        return view('administrarusuarios', compact('usuarios'));
    }


    /**
     * Pantalla de editar el rol de un usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
        if ($request->user()->rol!='administrador'){
            abort (403);
        }

        $usuario = User::find($id);
        return view('editarusuario', compact('usuario'));
    }

    /**
     * Actualiza el rol de un usuario
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if ($request->user()->rol!='administrador'){
            abort (403);
        }

        $usuario = User::find($id);
        $usuario->rol = $request->input('rol');
        $usuario->save();

        return redirect(url('usuarios'));
    }
    
    /**
     * (amondejar)
     * Comprobamos si hay que mostrar el modal de recompensa por primer login
     */
    public function mostrarModalFirstLogin(Request $request)
    {
        return $request->user()->mostrarModalFirstLogin($request);
    }
}
