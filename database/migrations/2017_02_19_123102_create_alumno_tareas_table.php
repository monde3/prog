<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnoTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumno_tareas', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->default(1);
            $table->string('cod_tarea', 10);
            $table->integer('curso_academico');

            //Primary key
            $table->unique(['user_id', 'cod_tarea', 'curso_academico']);

            //Alumno
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            //Tarea
            $table->foreign('cod_tarea')
                  ->references('cod_tarea')->on('tareas')
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
        Schema::dropIfExists('alumno_tareas');
    }
}
