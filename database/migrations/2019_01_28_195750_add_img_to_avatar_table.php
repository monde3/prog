<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImgToAvatarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('avatar', function (Blueprint $table) {
            $table->integer('img_activo')->default(0);
            $table->integer('img_inactivo')->default(0);
            $table->integer('img_herido')->default(0);
            $table->integer('img_head')->default(0);
            $table->integer('img_body')->default(0);
            $table->integer('img_hands')->default(0);
            $table->integer('img_feet')->default(0);
            $table->integer('img_weapon')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('avatar', function (Blueprint $table) {
            $table->dropColumn('img_activo');
            $table->dropColumn('img_inactivo');
            $table->dropColumn('img_herido');
            $table->dropColumn('img_head');
            $table->dropColumn('img_body');
            $table->dropColumn('img_hands');
            $table->dropColumn('img_feet');
            $table->dropColumn('img_weapon');
        });
    }
}
