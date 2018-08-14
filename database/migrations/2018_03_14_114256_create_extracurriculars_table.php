<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtracurricularsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('term_id')->unsigned(); // ID tahun ajaran
            $table->string('name')->unique(); // Nama ekstrakurikuler

            $table->foreign('term_id')->references('id')->on('terms')
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
        Schema::dropIfExists('extracurriculars');
    }
}
