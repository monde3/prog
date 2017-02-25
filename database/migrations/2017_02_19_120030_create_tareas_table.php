<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            
            $table->string('cod_tarea', 10)->primary();
            $table->string('titulo', 100);
            $table->string('des_tarea', 255);
            $table->float('tiempo_estimado', 4, 2);
            $table->dateTime('fecha_fin');
            
            //Propietario de la tarea
            $table->integer('propierio_id')->unsigned()->index()->default(1);
            $table->foreign('propierio_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');;
            
            //Asignatura a la que pertenece la tarea
            $table->string('cod_asi', 4)->index();
            $table->foreign('cod_asi')
                  ->references('cod_asi')->on('asignaturas')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tareas');
    }
}
