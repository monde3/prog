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
        'dni', 'nombre', 'apellidos', 'email', 'password', 'rol',
    ];

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


    public function numNotificaciones(){
        $tareasAlumno = AlumnoTarea::where('user_id', $this->id)->get();

        $tareasAlumno = $tareasAlumno->reject(function ($tareaAlumno) {
            return  (
                    Carbon::parse($tareaAlumno->tarea->fecha_fin) < Carbon::now('Europe/Madrid') or
                    $tareaAlumno->tarea->tiempoRestante() > Tarea::ALERTA_AMARILLA
                    );
        });
        
        $numNotificaciones = $tareasAlumno->count();

        return $numNotificaciones;
    }

    public function numNotificacionesAlertaRoja(){
        $tareasAlumno = AlumnoTarea::where('user_id', $this->id)->get();

        $tareasAlumno = $tareasAlumno->reject(function ($tareaAlumno) {
            return  (
                    Carbon::parse($tareaAlumno->tarea->fecha_fin) < Carbon::now('Europe/Madrid') or
                    $tareaAlumno->tarea->tiempoRestante() > Tarea::ALERTA_ROJA
                    );
        });
        
        $numNotificaciones = $tareasAlumno->count();

        return $numNotificaciones;
    }

    public function numNotificacionesAlertaAmarilla(){
        $tareasAlumno = AlumnoTarea::where('user_id', $this->id)->get();

        $tareasAlumno = $tareasAlumno->reject(function ($tareaAlumno) {
            return  (
                    Carbon::parse($tareaAlumno->tarea->fecha_fin) < Carbon::now('Europe/Madrid') or
                    $tareaAlumno->tarea->tiempoRestante() > Tarea::ALERTA_AMARILLA or
                    $tareaAlumno->tarea->tiempoRestante() < Tarea::ALERTA_ROJA
                    );
        });
        
        $numNotificaciones = $tareasAlumno->count();

        return $numNotificaciones;
    }

    public function misTareasActivas()
    {
        $ahora = Carbon::now();
        $alumnoId = $this->id;

        $tareas = DB::select(DB::raw( 
                "select at.id as alumno_tarea_id,
                        at.alumno_id,
                        at.cod_tarea,
                        at.curso_academico,
                        mt.tiempo,
                        t.titulo,
                        t.des_tarea,
                        t.tiempo_estimado,
                        t.fecha_fin,
                        asi.cod_asi,
                        asi.des_asi,
                        ROUND(tiempo*100/t.tiempo_estimado,2) porcentaje
                   from (select ata.id,
                                SUM(IFNULL(tt.inicio, '$ahora') - 
                                    IFNULL(tt.fin, IF(ISNULL(tt.inicio), '$ahora', 
                                                                         IF('$ahora'>t.fecha_fin, t.fecha_fin, 
                                                                                                  '$ahora')))) tiempo
                           from alumno_tareas ata left outer join tiempo_tareas tt
                             on ata.id = tt.alumno_tarea_id,
                                tareas t
                          where ata.alumno_id = '$alumnoId'
                            and ata.cod_tarea = t.cod_tarea
                            and t.fecha_fin > '$ahora'
                          group by ata.id) mt,
                        alumno_tareas at,
                        tareas t,
                        asignaturas asi
                  where at.id = mt.id
                    and at.cod_tarea = t.cod_tarea
                    and asi.cod_asi = t.cod_asi"));
      
        return $tareas;

    }
}
