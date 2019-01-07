<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dni', 8)->unique();
            $table->string('nombre', 50);
            $table->string('apellidos', 100);
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->enum('rol', ['alumno', 'profesor', 'administrador']);
            $table->boolean('activo');
            //Gamificación
            $table->dateTime('last_login')->comment('Fecha de último login');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
