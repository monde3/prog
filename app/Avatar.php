<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Imagen;

class Avatar extends Model
{
    /**
     * Atributos que son masivamente asignables
     *
     * @var array
     */
    protected $fillable = [
        'user_id','oro','exp','vida','head','body','hands','feet','weapon','estado','img_avatar','img_head','img_body','img_hands','img_feet','img_weapon'
    ];

    /*
     (amondejar)
     */
    const FACTOR_SUBIDA_NIVEL = 100; // Lentitud a la que se sube de nivel
    const VIDA_MAXIMA = 100; // Puntuación máxima de vida que se puede obtener
    const EXP_ADICIONAL = 20; // Porcentaje de puntos de vida, adicionales al sumar experiencia
    const ORO_VICTORIA = 10; // Puntos de oro por ganar una batalla
    const ORO_LEVEL_UP = 50; // Puntos de oro por subir de nivel
    const VIDA_HERIDO = 20; // Puntos de vida mínimos para estado activo/inactivo
    const NUMERO_PARTES_ARMADURA = 4; // Número de piezas que forman la armadura de un avatar

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
    
    /**
     * Obtiene las imagenes que tiene asociadas un avatar
     */
    public function avatarImagenes()
    {
        return $this->hasMany('App\AvatarImagen');
    }

    // (amondejar)
    // Calculamos el nivel en el que nos encontramos
    public function nivelAvatar()
    {
            return floor(sqrt($this->exp/Self::FACTOR_SUBIDA_NIVEL));
    }

    // (amondejar) Puntos que hemos conseguido en el nivel actual
    public function porcentajeNivel(){
        if ($this->alumno->rol != 'alumno'
            || $this->exp == 0){
            return 0;
        }
        $nivel = $this->nivelAvatar();
        $proximo_nivel = $nivel + 1;
        $puntos_nivel_actual = pow($nivel, 2) * Self::FACTOR_SUBIDA_NIVEL;
        $puntos_nivel_proximo = pow($proximo_nivel, 2) * Self::FACTOR_SUBIDA_NIVEL;

        // Puntos que hemos ganado en el nivel actual
        $puntos_en_nivel = $this->exp - $puntos_nivel_actual;

        // Puntos desde nivel actual hasta proximo nivel
        $puntos_nivel = $puntos_nivel_proximo - $puntos_nivel_actual;

        $porcentaje = $puntos_en_nivel * 100 / $puntos_nivel;
        return floor($porcentaje);
    }

    /** (amondejar)
     * Aumenta los puntos de experiencia
     *
     * @param $cantidad - Puntos a sumar
     * @return booleano - true si aumenta de nivel y false si no
     */
    public function sumarExperiencia($cantidad){
        $puntos_adicionales = intdiv($this->vida, Self::EXP_ADICIONAL);
        $nivel_antes = $this->nivelAvatar();
        
        $this->exp += $cantidad + $puntos_adicionales;
        $this->save();

        if ($nivel_antes != $this->nivelAvatar()){
            $this->sumarOro(Self::ORO_LEVEL_UP);
            return 1;
        }
        return 0;
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
        if (($this->oro - $cantidad) < 0) {
            return false;
        } else {
            $this->oro -= $cantidad;
        }
        $this->save();
        return true;
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

    public function sumarFeet($cantidad){
            $this->feet += $cantidad;
            $this->save();
    }

    public function sumarWeapon($cantidad){
            $this->weapon += $cantidad;
            $this->save();
    }

    public function imagePath($parte){
        $this->estadoActual();

        $image_id = 0;

        if(($parte == 'avatar')){
            if(($this->estado == 'activo')){
                $image_id = $this->img_activo;
            }
            if(($this->estado == 'inactivo')){
                $image_id = $this->img_inactivo;
            }
            if(($this->estado == 'herido')){
                $image_id = $this->img_herido;
            }
        }else if(($parte == 'head')){
            $image_id = $this->img_head;
        }else if(($parte == 'body')){
            $image_id = $this->img_body;
        }else if(($parte == 'hands')){
            $image_id = $this->img_hands;
        }else if(($parte == 'feet')){
            $image_id = $this->img_feet;
        }else if(($parte == 'weapon')){
            $image_id = $this->img_weapon;
        }

        $imagen = Imagen::find($image_id);
        
        $image_path = '';
        if(isset($imagen)){
            $image_path = Imagen::where('id', $image_id)->firstOrFail()->filename;
        }

        // Primero vemos si existe la imagen personalizada
        $exists = Storage::disk('images')->exists($image_path);

        // En caso de que no exista o no esté configurada
        if(($parte=='avatar') && ($this->img_avatar == 0) && (!$exists)){
            if($this->estado == 'activo'){
                $image_path = 'avatar-activo.png';
            }
            if($this->estado == 'inactivo'){
                $image_path = 'avatar-inactivo.png';
            }
            if($this->estado == 'herido'){
                $image_path = 'avatar-herido.png';
            }
        }
        return $image_path;
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
            $puntos_usuario = $this->head + $this->body + $this->hands + 
                        $this->feet + $this->weapon;
            $puntos_oponente = $oponente->head + $oponente->body + $oponente->hands + 
                        $oponente->feet + $oponente->weapon;

            $numero_aleatorio = rand();

            $proporcion = $puntos_usuario / ($puntos_usuario + $puntos_oponente);

            // Valor para comparar con el aleatorio que hemos obtenido
            $separador = getrandmax() * $proporcion;

            // La victoria ocurrirá cuando el número aleatorio pertenezca
            // a la parte de números aleatorios que corresponde al
            // usuario que inicia el combate
            $victoria = $numero_aleatorio <= $separador;

            $armadura_usuario = ($this->head + $this->body + $this->hands + $this->feet)
                                    / Self::NUMERO_PARTES_ARMADURA;

            // Comparamos la media de los puntos de armadura del usuario
            // con los puntos del arma del oponente
            if($victoria){
                $vida_restar = (abs($armadura_usuario-$oponente->weapon) > 10 ? 0
                                 : ($armadura_usuario>$oponente->weapon ? 1 : 2));
                $this->sumarOro(Self::ORO_VICTORIA);
            }else{
                $vida_restar = (abs($armadura_usuario-$oponente->weapon) > 10 ? 3
                                 : ($armadura_usuario>$oponente->weapon ? 4 : 5));
            }
            $this->restarVida($vida_restar * 2);
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

    /**
     * Actualiza el estado en función de la vida que le quede
     */
    public function estadoActual(){
        if($this->vida < 20){
            $this->cambiarEstado('herido');
        }else{
            if($this->estado == 'herido'){
                $this->cambiarEstado('inactivo');
            }
        }
    }

}
