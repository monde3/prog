<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

use App\AlumnoTarea;
use App\Tarea;
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
        'dni', 'nombre', 'apellidos', 'email', 'password', 'rol', 'activo', 'oro', 'exp', 'vida', 'last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*
     Intervalo con el que se sube de nivel (amondejar)
     */
    const PUNTOS_POR_NIVEL = 50; //Cada 50 puntos subiremos de nivel


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

    // (amondejar) Nos indica si debemos mostrar el modal de primer login
    public function mostrarModalFirstLogin(){
        if ($this->rol != 'alumno'){
            return 0;
        }

        // Si es el primer login del día guardamos la fecha y otorgamos 5 exp (amondejar)
        $dateLastLogin = Carbon::parse(Carbon::parse($this->last_login)->toDateString());
        $today = Carbon::today();
        $mostrarModal = $dateLastLogin->diffInDays($today);

        if ($mostrarModal > 0){
            $this->last_login = $today;
            $this->save();
        }
        return $mostrarModal;
    }

    public function porcentajeNivel(){
        if ($this->rol != 'alumno'){
            return 0;
        }

        //Puntos que hemos conseguido en el nivel actual (amondejar)
        $puntosEnNivel = $this->exp % User::PUNTOS_POR_NIVEL;
        $porcentaje = $puntosEnNivel * 100 / User::PUNTOS_POR_NIVEL;
        
        return $porcentaje;
    }

    public function sumarExperiencia($cantidad){
            $this->exp += $cantidad;
            $this->save();
    }

    public function sumarVida($cantidad){
            $this->vida += $cantidad;
            $this->save();
    }

    public function sumarOro($cantidad){
            $this->oro += $cantidad;
            $this->save();
    }
}
