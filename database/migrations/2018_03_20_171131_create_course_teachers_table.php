<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('course_id')->unsigned(); // ID Mata pelajaran
            $table->integer('teacher_id')->unsigned()->nullable(); // ID Guru
            $table->integer('room_term_id')->unsigned(); // ID Kelas-TahunAjaran-Semester
        
            $table->unique(['course_id', 'teacher_id', 'room_term_id']);

            $table->foreign('course_id')
                ->references('id')->on('courses');

            $table->foreign('teacher_id')
                ->references('id')->on('teachers');

            $table->foreign('room_term_id')
                ->references('id')->on('room_terms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_teachers');
    }
}
