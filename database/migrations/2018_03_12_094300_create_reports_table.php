<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('student_id')->unsigned(); // ID siswa
            $table->integer('room_term_id')->unsigned(); // ID kelas (room term == kelas)

            $table->unique(['student_id', 'room_term_id']);

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('room_term_id')->references('id')->on('room_terms');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
