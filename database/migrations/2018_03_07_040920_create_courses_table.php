<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->index(); // Nama mata pelajaran
            $table->integer('passing_grade'); // KKM
            $table->enum('group', ['A', 'B']); // Kelompok mata pelajarn (A atau B)
            $table->text('description'); // Deskripsi mata pelajaran (Kurikulum, kelas, dsb.)
            $table->boolean('has_spiritual_grades'); // Memiliki nilai spiritual / tidak
            $table->boolean('has_social_grades'); // Memiliki nilai sosial / tidak

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
        Schema::dropIfExists('courses');
    }
}
