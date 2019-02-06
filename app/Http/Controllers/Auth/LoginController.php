<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        login as protected traitlogin;
    }

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest', ['except' => 'logout']);
    }


    /**
     * (amondejar)
     * Sobreescribimos la función de login del TRAIT AuthenticatesUsers
     * para gestionar la recompensa por primer login del dia
     *
     */
    public function login(Request $request)
    {
        $returnLogin = $this->traitlogin($request);
        if (isset($request->user()->id) and ($request->user()->rol == 'alumno')) {
            $request->user()->guardarLastLogin($request);
        }
        return $returnLogin;
    }

}
