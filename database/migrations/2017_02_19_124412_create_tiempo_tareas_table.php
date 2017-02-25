<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiempoTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiempo_tareas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('alumno_id')->unsigned()->index()->default(1);
            $table->string('cod_tarea');
            $table->integer('curso_academico');
            $table->dateTime('inicio');
            $table->dateTime('fin');

            //Foreign keys
            $table->foreign(['alumno_id', 'cod_tarea', 'curso_academico'])
                  ->references(['alumno_id', 'cod_tarea', 'curso_academico'])->on('alumno_tareas')
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
        Schema::dropIfExists('tiempo_tareas');
    }
}
