<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvatarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avatar', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->default(1);
            $table->integer('oro');
            $table->integer('exp');
            $table->integer('vida');
            $table->integer('head');
            $table->integer('body');
            $table->integer('hands');
            $table->integer('feet');
            $table->integer('weapon');
            $table->enum('estado', ['activo', 'inactivo', 'herido']);

            $table->unique(['user_id']);
            //Alumno
            $table->foreign('user_id')
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
        Schema::dropIfExists('avatar');
    }
}
