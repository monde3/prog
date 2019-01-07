<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Avatar;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {   

        $messages = [
            'required'    => 'El campo :attribute es obligatorio.',
            'size'        => 'El tamaño del campo :attribute debe ser excatamente :size caracteres.',
            'max'         => 'El tamaño del campo :attribute debe no debe ser mayor de :max caracteres.',
            'min'         => 'El tamaño del campo :attribute debe no debe ser mayor de :min caracteres.',
            'unique'      => 'Ya existe un usuario con el mismo :attribute.',
            'confirmed'   => 'La confirmación del :attribute no coincide.',
            'size'        => 'El tamaño del campo :attribute debe ser excatamente :size caracteres.',
        ];


        $validador = Validator::make($data, [
            'dni' => 'required|size:8',
            'nombre' => 'required|max:50',
            'apellidos' => 'required|max:100',
            'email' => 'required|email|max:50',
            'password' => 'required|min:6|max:20|confirmed',
        ], $messages);

        if ($validador->fails()){
            return $validador;
        }

        $usuario = User::where('dni', $data['dni'])->where('email', $data['email'])->first();

        if(isset($usuario)){
            if ($usuario->activo){
                $validador->after(function($validador){
                    $validador->errors()->add('dni', 'Ya existe un usuario con el mismo :attribute');
                });
            }
        } else{
            $validador = Validator::make($data, [
                'dni' => 'unique:users,dni',
                'email' => 'unique:users,email',
            ], $messages);   
        }
        return $validador;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {   
        $usuario = User::where('dni', $data['dni'])->where('email', $data['email'])->first();

        if(!isset($usuario)){
        // Si no hemos encontrado el usuario creamos uno nuevo
            $user = User::create([
                'dni' => $data['dni'],
                'nombre' => $data['nombre'],
                'apellidos' => $data['apellidos'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'rol' => 'alumno',
                'activo' => true,
                'oro' => 0,
                'exp' => 0,
                'vida' => 0,
                'last_login' => Carbon::now(),
            ]);
            Avatar::create([
                'user_id' => $user->id,
                'oro' => 0,
                'exp' => 0,
                'vida' => 0,
                'head' => 0,
                'body' => 0,
                'hands' => 0,
                'foot' => 0,
                'weapon' => 0,
                'estado' => 'activo',
            ]);
            return $user;
        } else { 
        // Si el usuario ya existe, lo creamos desde cero si la contraseña es la misma
        // y le mantenemos los valores del avatar
            $usuario->nombre = $data['nombre'];
            $usuario->apellidos = $data['apellidos'];
            $usuario->email = $data['email'];
            $usuario->password = $data['password'];
            $usuario->activo = true;
            $usuario->save();
        }

        return $usuario;
    }
}
