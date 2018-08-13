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
            $table->enum('group', ['A', 'B']); // Kelompok mata pelajarn (A atau B)
            $table->integer('grade')->unsigned()->index(); // Jenjang (7, 8, 9)
            $table->integer('term_id')->unsigned();
            $table->string('type'); // Tipe penilaian (normal, spiritual, sosial)

            $table->foreign('term_id')->references('id')->on('terms')
                ->onDelete('cascade');

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
