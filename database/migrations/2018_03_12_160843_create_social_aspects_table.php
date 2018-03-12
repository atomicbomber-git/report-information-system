<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialAspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_aspects', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('name')->unique(); // Nama aspek (berdoa, salam, tawakal, ...)
            
            // Nilai ke-1 sampai ke-7
            $table->double('score_1')->nullable();
            $table->double('score_2')->nullable();
            $table->double('score_3')->nullable();
            $table->double('score_4')->nullable();
            $table->double('score_5')->nullable();
            $table->double('score_6')->nullable();
            $table->double('score_7')->nullable();

            $table->integer('course_report_id')->unsigned(); // ID laporan nilai spiritual
            
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
        Schema::dropIfExists('social_aspects');
    }
}
