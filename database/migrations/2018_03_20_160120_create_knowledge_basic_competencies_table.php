<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKnowledgeBasicCompetenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_basic_competencies', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name'); // Nama mata pelajaran
            $table->string('even_odd'); // Semester genap / ganjil
            $table->integer('course_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('course_id')->references('id')
                ->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knowledge_basic_competencies');
    }
}
