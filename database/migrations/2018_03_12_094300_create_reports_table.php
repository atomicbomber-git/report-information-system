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
            $table->integer('room_term_id')->unsigned()->index(); // ID kelas (room term == kelas)
            $table->unique(['room_term_id', 'student_id']);

            $table->integer('absence_sick')->unsigned()->default(0); // Sakit
            $table->integer('absence_permit')->unsigned()->default(0); // Izin
            $table->integer('absence_unknown')->unsigned()->default(0); // Alpa

            $table->text('social_attitude_description')->nullable(); // Deskripsi sikap sosial
            $table->text('spiritual_attitude_description')->nullable(); // Deskripsi sikap spiritual

            $table->foreign('student_id')->references('id')->on('students')
                ->onDelete('cascade');
            $table->foreign('room_term_id')->references('id')->on('room_terms')
                ->onDelete('cascade');
            
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
