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
     (amondejar)
     */
    const PUNTOS_POR_NIVEL = 100; //Intervalo con el que se sube de nivel
    const VIDA_MAXIMA = 100; // Puntuación máxima de vida que se puede obtener
    const EXP_ADICIONAL = 20; // Porcentaje de puntos de vida, adicionales al sumar experiencia


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
            $this->sumarExperiencia(5);
            $this->last_login = $today;
            $this->save();
            $mostrarModal = $this->exp;
        }
        return $mostrarModal;
    }

    // (amondejar) Puntos que hemos conseguido en el nivel actual
    public function porcentajeNivel(){
        if ($this->rol != 'alumno'){
            return 0;
        }
        $puntosEnNivel = $this->exp % Self::PUNTOS_POR_NIVEL;
        $porcentaje = $puntosEnNivel * 100 / Self::PUNTOS_POR_NIVEL;

        return $porcentaje;
    }

    public function sumarExperiencia($cantidad){
        $puntos_adicionales = intdiv($this->vida, Self::EXP_ADICIONAL);

        $this->exp += $cantidad;
        $this->save();
    }

    public function sumarVida($cantidad){
        if ($this->vida < Self::VIDA_MAXIMA) {
            $this->vida += $cantidad;
        } else if (($this->vida + $cantidad) > Self::VIDA_MAXIMA) {
            $this->vida = Self::VIDA_MAXIMA;
        }
        
        $this->save();
    }

    public function sumarOro($cantidad){
            $this->oro += $cantidad;
            $this->save();
    }

    public function restarOro($cantidad){
            $this->oro -= $cantidad;
            $this->save();
    }
}
