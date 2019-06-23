<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('alumno_tarea_id')->unsigned()->index()->nullable();
            $table->integer('oponente_id')->unsigned()->index()->nullable();
            $table->string('texto', 255)->default('');
            $table->string('resultado', 255)->default('');
            $table->boolean('activa')->default(1);
            //Alumno
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            //Alumno
            $table->foreign('alumno_tarea_id')
                  ->references('id')->on('alumno_tareas')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            //Oponente
            $table->foreign('oponente_id')
                  ->references('id')->on('users')
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
        Schema::dropIfExists('notificaciones');
    }
}
