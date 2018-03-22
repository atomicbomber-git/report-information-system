<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKnowledgeGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('course_report_id')->unsigned()->index(); // ID laporan mapel
            $table->integer('knowledge_basic_competency_id')->unsigned(); // ID laporan mata pelajaran

            $table->double('first_assignment')->default(0); // Penugasan 1
            $table->double('second_assignment')->default(0); // Penugasan 2
            $table->double('third_assignment')->default(0); // Penugasan 3

            $table->double('first_exam')->default(0); // Ujian 1
            $table->double('first_remedial')->default(0); // Remedial 1
            $table->double('second_exam')->default(0); // Ujian 2
            $table->double('second_remedial')->default(0); // Remedial 2

            $table->unique(['course_report_id', 'knowledge_basic_competency_id'], 'course_report_bc_unique');

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
        Schema::dropIfExists('knowledge_grade');
    }
}
