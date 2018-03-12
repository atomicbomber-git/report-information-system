<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseRoomSemestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_room_semesters', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('course_id')->unsigned();
            $table->integer('room_semester_id')->unsigned();
            $table->unique(['course_id', 'room_semester_id']);

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('room_semester_id')->references('id')->on('room_semesters');

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
        Schema::dropIfExists('course_room_semesters');
    }
}
