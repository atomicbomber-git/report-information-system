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
            $table->integer('teacher_id')->unsigned(); // ID guru pembimbing
            $table->string('name'); // Nama ekstrakurikuler

            $table->unique(['term_id', 'name']);

            $table->foreign('term_id')
                ->references('id')
                ->on('terms')
                ->onDelete('cascade');

            $table->foreign('teacher_id')
                ->references('id')
                ->on('teachers')
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
