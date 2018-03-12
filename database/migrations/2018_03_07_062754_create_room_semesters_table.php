<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomSemestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_semesters', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('room_id')->unsigned();
            $table->integer('semester_id')->unsigned();

            $table->foreign('room_id')->references('id')->on('rooms');
            $table->foreign('semester_id')->references('id')->on('semesters');

            $table->unique(['room_id', 'semester_id']);

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
        Schema::dropIfExists('room_semesters');
    }
}
