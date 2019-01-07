<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    /**
     * Atributos que son masivamente asignables
     *
     * @var array
     */
    protected $fillable = [
        'user_id','oro','exp','vida','head','body','hands','foot','weapon','estado'
    ];

    /*
     (amondejar)
     */
    const PUNTOS_POR_NIVEL = 100; //Intervalo con el que se sube de nivel
    const VIDA_MAXIMA = 100; // Puntuación máxima de vida que se puede obtener
    const EXP_ADICIONAL = 20; // Porcentaje de puntos de vida, adicionales al sumar experiencia
    const ORO_VICTORIA = 10; // Puntos de oro por ganar una batalla
    const VIDA_HERIDO = 20; // Puntos de vida mínimos para estado activo/inactivo

    /**
     * Primary key
     */
    protected $primaryKey = 'user_id';

    protected $table = 'avatar';
    
    /*
     * No guardamos cuando ha sido creado y/o actualizado un registro
     */
    public $timestamps = false;

    /**
     * Obtiene el usuario del avatar
     */
    public function alumno()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // (amondejar) Puntos que hemos conseguido en el nivel actual
    public function porcentajeNivel(){
        if ($this->alumno->rol != 'alumno'){
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

    public function restarExperiencia($cantidad){
        if (($this->exp - $cantidad) <= 0) {
            $this->exp = 0;
        } else if (($this->exp - $cantidad) > 0) {
            $this->exp -= $cantidad;
        }
        $this->save();
    }

    public function sumarVida($cantidad){
        if (($this->vida + $cantidad) > Self::VIDA_MAXIMA) {
            $this->vida = Self::VIDA_MAXIMA;
        } else if (($this->vida + $cantidad) <= Self::VIDA_MAXIMA) {
            $this->vida += $cantidad;
        }
        
        $this->save();
    }

    public function restarVida($cantidad){
        if (($this->vida - $cantidad) <= 0) {
            $this->vida = 0;
        } else if (($this->vida - $cantidad) > 0) {
            $this->vida -= $cantidad;
        }
        if ($this->vida < Self::VIDA_HERIDO){
            $this->cambiarEstado('herido');
        }
        $this->save();
    }

    public function sumarOro($cantidad){
        $this->oro += $cantidad;
        $this->save();
    }

    public function restarOro($cantidad){
        if (($this->oro - $cantidad) <= 0) {
            $this->oro = 0;
        } else if (($this->oro - $cantidad) > 0) {
            $this->oro -= $cantidad;
        }
        $this->save();
    }

    public function sumarHead($cantidad){
            $this->head += $cantidad;
            $this->save();
    }

    public function sumarBody($cantidad){
            $this->body += $cantidad;
            $this->save();
    }

    public function sumarHands($cantidad){
            $this->hands += $cantidad;
            $this->save();
    }

    public function sumarFoot($cantidad){
            $this->foot += $cantidad;
            $this->save();
    }

    public function sumarWeapon($cantidad){
            $this->weapon += $cantidad;
            $this->save();
    }

    public function rutaImagen(){
        if($this->vida < 20){
            $this->cambiarEstado('herido');
        }
        $url = '';
        if($this->estado == 'activo'){
            $url = asset('images/avatar-activo.png');
        }
        if($this->estado == 'inactivo'){
            $url = asset('images/avatar-inactivo.png');
        }
        if($this->estado == 'herido'){
            $url = asset('images/avatar-herido.png');
        }
        return $url;
    }

    /**
     * Calcula la lucha contra otro avatar:
     *     Se reparten los números del intervalo de aleatorios mínimo y máximo
     *   entre los dos usuarios. Si el número aleatorio cae en la parte del usuario
     *   habrá una victoria; y una derrota en caso contrario.
     *     Resta puntos de vida en función de la armadura del usuario y el arma
     *   del oponente.
     *     Suma los puntos de oro predefinidos en caso de victoria
     * @param $oponente - Avatar contra el que se lucha
     * @return booleano - true si gana y false si pierde
     */
    public function lucha($oponente){
        if ($this->vida >= 20 and $this->estado = 'activo'){
            // Obtenemos la suma de los puntos de cada usuario
            $points_usuario = $this->head + $this->body + $this->hands + 
                        $this->foot + $this->weapon;
            $points_oponente = $oponente->head + $oponente->body + $oponente->hands + 
                        $oponente->foot + $oponente->weapon;

            $aleatorio = rand();

            // 
            $proporcion = $points_usuario / ($points_usuario + $points_oponente);
            // Valor para comparar con el aleatorio que hemos obtenido
            $separador = getrandmax() * $proporcion;
            $victoria = $aleatorio > $separador;

            $armadura_usuario = $this->head + $this->body + $this->hands + $this->foot;
            // Comparamos la media de los puntos de armadura del usuario con los puntos del arma del oponente
            if($victoria){
                $vida_restar = (abs($armadura_usuario-$oponente->weapon) > 10 ? 1
                                 : ($armadura_usuario<$oponente->weapon ? 0 : 2));
                $this->sumarOro(Self::ORO_VICTORIA);
            }else{
                $vida_restar = (abs($armadura_usuario-$oponente->weapon) > 10 ? 4
                                 : ($armadura_usuario<$oponente->weapon ? 3 : 5));
            }
            $this->restarVida($vida_restar * 5);
        }
        else{
            $victoria = false;
        }

        return $victoria;
    }

    /**
     * Cambia de estado
     *
     * @param $oponente - Avatar contra el que se lucha
     * @return booleano - true si gana y false si pierde
     */
    public function cambiarEstado($estado){
        if( ($estado == 'herido' or $estado == 'activo' or $estado == 'inactivo') and
          ( ($this->vida >= 20 and $estado != 'herido') or
             ($this->vida < 20 and $estado == 'herido') )){
            $this->estado = $estado;
            $this->save();
        }
    }

}
