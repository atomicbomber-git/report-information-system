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

            $table->integer('course_report_id')->unsigned(); // ID laporan mapel
            $table->integer('knowledge_basic_competency_id')->unsigned(); // ID laporan mata pelajaran

            $table->double('first_assignment')->nullable(); // Penugasan 1
            $table->double('second_assignment')->nullable(); // Penugasan 2
            $table->double('third_assignment')->nullable(); // Penugasan 3

            $table->double('first_exam')->nullable(); // Ujian 1
            $table->double('first_remedial')->nullable(); // Remedial 1
            $table->double('second_exam')->nullable(); // Ujian 2
            $table->double('second_remedial')->nullable(); // Remedial 2

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
