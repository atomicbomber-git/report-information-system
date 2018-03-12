<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillAspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skill_aspects', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('name')->unique(); // Nama aspek penilaian keterampilan (praktek, produk, portofolio ...)

            // Nilai ke-1 sampai nilai ke-6
            $table->double('score_1')->nullable();
            $table->double('score_2')->nullable();
            $table->double('score_3')->nullable();
            $table->double('score_4')->nullable();
            $table->double('score_5')->nullable();
            $table->double('score_6')->nullable();

            $table->integer('course_report_id')->unsigned(); // ID laporan keterampilan

            $table->foreign('course_report_id')->references('id')->on('course_reports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skill_aspects');
    }
}
