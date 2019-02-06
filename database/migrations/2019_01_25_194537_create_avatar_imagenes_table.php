<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvatarImagenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avatar_imagenes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('avatar_id')->unsigned()->index()->default(1);
            $table->integer('imagen_id')->unsigned()->index()->default(1);

            //Primary key
            $table->unique(['avatar_id', 'imagen_id']);

            //Alumno
            $table->foreign('avatar_id')
                  ->references('id')->on('avatar')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            //Imagen
            $table->foreign('imagen_id')
                  ->references('id')->on('imagenes')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avatar_imagenes');
    }
}
