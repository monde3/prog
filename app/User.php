<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\AlumnoTarea;
use App\Tarea;
use App\Avatar;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dni', 'nombre', 'apellidos', 'email', 'password', 'rol', 'activo', 'last_login'
    ];

    /*
     (amondejar)
     */
    const DIAS_SIN_ACCEDER = 5; //Días sin acceder para que se resten puntos de exp

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Obtiene las tareas del alumno.
     */
    public function tareasAlumno()
    {
        return $this->hasMany('App\AlumnoTarea');
    }

    /**
     * Obtiene las tareas del profesor.
     */
    public function tareasProfesor()
    {
        return $this->hasMany('App\Tarea');
    }
    
    /**
     * Obtiene el avatar del alumno
     */
    public function avatar()
    {
        return $this->hasOne('App\Avatar', 'user_id', 'id');
    }


    public function numNotificaciones(){

        $numNotificaciones = 0;

        if ($this->rol == 'alumno') {        
            $tareasAlumno = AlumnoTarea::where('user_id', $this->id)->get();

            $tareasAlumno = $tareasAlumno->reject(function ($tareaAlumno) {
                return  (
                        Carbon::parse($tareaAlumno->tarea->fecha_fin) < Carbon::now('Europe/Madrid') or
                        $tareaAlumno->tarea->tiempoRestante() > Tarea::ALERTA_AMARILLA
                        );
            });

            $numNotificaciones = $tareasAlumno->count();
        } elseif ($this->rol == 'profesor'){
            $tareasProfesor = Tarea::where('propietario_id', $this->id)->get();

            $tareasProfesor = $tareasProfesor->reject(function ($tareaProfesor) {
                return  (
                        Carbon::parse($tareaProfesor->fecha_fin) < Carbon::now('Europe/Madrid') or
                        $tareaProfesor->tiempoRestante() > Tarea::ALERTA_AMARILLA
                        );
            });

            $numNotificaciones = $tareasProfesor->count();
        }
        
            

        return $numNotificaciones;
    }

    public function numNotificacionesAlertaRoja(){
        $numNotificaciones = 0;
        
        if ($this->rol == 'alumno') {
            $tareasAlumno = AlumnoTarea::where('user_id', $this->id)->get();

            $tareasAlumno = $tareasAlumno->reject(function ($tareaAlumno) {
                return  (
                        Carbon::parse($tareaAlumno->tarea->fecha_fin) < Carbon::now('Europe/Madrid') or
                        $tareaAlumno->tarea->tiempoRestante() > Tarea::ALERTA_ROJA
                        );
            });
            
            $numNotificaciones = $tareasAlumno->count();
        } elseif ($this->rol == 'profesor'){
            $tareasProfesor = Tarea::where('propietario_id', $this->id)->get();

            $tareasProfesor = $tareasProfesor->reject(function ($tareaProfesor) {
                return  (
                        Carbon::parse($tareaProfesor->fecha_fin) < Carbon::now('Europe/Madrid') or
                        $tareaProfesor->tiempoRestante() > Tarea::ALERTA_ROJA
                        );
            });

            $numNotificaciones = $tareasProfesor->count();
        }

        return $numNotificaciones;
    }

    public function numNotificacionesAlertaAmarilla(){
        $numNotificaciones = 0;
        
        if ($this->rol == 'alumno') {
            $tareasAlumno = AlumnoTarea::where('user_id', $this->id)->get();

            $tareasAlumno = $tareasAlumno->reject(function ($tareaAlumno) {
                return  (
                        Carbon::parse($tareaAlumno->tarea->fecha_fin) < Carbon::now('Europe/Madrid') or
                        $tareaAlumno->tarea->tiempoRestante() > Tarea::ALERTA_AMARILLA or
                        $tareaAlumno->tarea->tiempoRestante() < Tarea::ALERTA_ROJA
                        );
            });
            
            $numNotificaciones = $tareasAlumno->count();
        } elseif ($this->rol == 'profesor'){
            $tareasProfesor = Tarea::where('propietario_id', $this->id)->get();

            $tareasProfesor = $tareasProfesor->reject(function ($tareaProfesor) {
                return  (
                        Carbon::parse($tareaProfesor->fecha_fin) < Carbon::now('Europe/Madrid') or
                        $tareaProfesor->tiempoRestante() > Tarea::ALERTA_AMARILLA or
                        $tareaProfesor->tiempoRestante() < Tarea::ALERTA_ROJA
                        );
            });
            
            $numNotificaciones = $tareasProfesor->count();
        }

        return $numNotificaciones;
    }

    // (amondejar)
    // Guardamos la fecha del último login
    // En variable de sesión de tipo FLASH indicamos que
    // hay que mostrar modal y sumar exp
    public function guardarLastLogin(Request $request){
        if ($request->user()->rol == 'alumno'){
            // Si es el primer login del día guardamos la fecha y otorgamos 5 exp (amondejar)
            $avatar = Avatar::where('user_id', $this->id)->get()->first();
            $dateLastLogin = Carbon::parse(Carbon::parse($this->last_login)->toDateString());
            $today = Carbon::today();

            $mostrarModal = $dateLastLogin->diffInDays($today);
            // Guardamos en variable de sesión flash
            $request->session()->put('mostrarModalFirstLogin', $mostrarModal);

            if ($mostrarModal >= 1){
                $this->last_login = Carbon::today();
                $this->save();
                if ($mostrarModal == 1){
                    $avatar->sumarExperiencia(5);
                }
                else if ($mostrarModal > Self::DIAS_SIN_ACCEDER){
                    $avatar->restarExperiencia(2);
                }
            }

        }
    }

    // (amondejar)
    // Nos indica si debemos mostrar el modal de primer login
    public function mostrarModalFirstLogin(Request $request){
        // Recuperamos la variable de sesión flash
        $mostrarModal = $request->session()->pull('mostrarModalFirstLogin');

        if (($this->rol == 'alumno') && isset($mostrarModal) && ($mostrarModal > 0))
        {
            if ($mostrarModal == 1){
                return 1;
            }
            else if ($mostrarModal > Self::DIAS_SIN_ACCEDER){
                return -1;
            }
        }
        return 0;
    }


    // (amondejar)
    // Puntos que hemos conseguido en el nivel actual
    public function porcentajeNivel(){
        if ($this->rol == 'alumno'){
            return $this->avatar->porcentajeNivel();
        }
    }
}
