<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_reports', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('course_id')->unsigned(); // ID mapel
            $table->integer('report_id')->unsigned(); // ID laporan mapel

            $table->double('mid_exam')->nullable(); // Nilai ujian mid semester
            $table->double('final_exam')->nullable(); // Nilai ujian semester
            $table->text('skill_description')->nullable(); // Deskripsi keterampilan
            $table->text('knowledge_description')->nullable(); // Deskripsi pengetahuan
            $table->text('social_attitude_description')->nullable(); // Deskripsi pengetahuan
            $table->text('spiritual_attitude_description')->nullable(); // Deskripsi pengetahuan

            $table->unique(['course_id', 'report_id']);

            $table->foreign('course_id')->references('id')->on('courses')
                ->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')
                ->onDelete('cascade');

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
        Schema::dropIfExists('course_reports');
    }
}
