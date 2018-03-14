<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtracurricularReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extracurricular_reports', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('report_id')->unsigned(); // ID laporan semester
            $table->integer('extracurricular_id')->unsigned(); // ID kegiatan ekstrakurikuler
            $table->enum('score', ['A', 'B', 'C', 'D', 'E']); // Nilai ekstrakurikuler (A - E)
            
            $table->foreign('report_id')->references('id')->on('reports');
            $table->foreign('extracurricular_id')->references('id')->on('extracurriculars');

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
        Schema::dropIfExists('extracurricular_reports');
    }
}
