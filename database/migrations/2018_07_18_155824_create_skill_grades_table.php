<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skill_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('course_report_id')->unsigned()->index(); // ID laporan mapel
            $table->integer('knowledge_basic_competency_id')->unsigned()->index(); // ID laporan mata pelajaran
            $table->string('type')->index();

            $table->double('score_1')->default(0); // Nilai 1
            $table->double('score_2')->default(0); // Nilai 2
            $table->double('score_3')->default(0); // Nilai 3
            $table->double('score_4')->default(0); // Nilai 4
            $table->double('score_5')->default(0); // Nilai 5
            $table->double('score_6')->default(0); // Nilai 6

            $table->foreign('course_report_id')->references('id')
                ->on('course_reports')->onDelete('cascade');

            $table->foreign('knowledge_basic_competency_id')->references('id')
                ->on('knowledge_basic_competencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skill_grades');
    }
}
