<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSyActiveGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sy_active_game', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('thief')->nullable();
            $table->integer('police')->nullable();
            $table->integer('activity');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sy_active_game');
    }
}
