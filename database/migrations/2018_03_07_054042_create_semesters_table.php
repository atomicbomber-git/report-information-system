<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemestersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->increments('id');

            $table->year('term_start'); // Tahun mulainya tahun ajaran
            $table->year('term_end'); // Tahun berakhirnya tahun ajaran
            $table->enum('odd_even', ['odd', 'even']); // Semester genap / ganjil
            $table->string('code')->unique(); // Kode gabungan dari tahun mulai, tahun akhir, dan genap / ganjil

            $table->unique(['term_start', 'term_end', 'odd_even']);
            
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
        Schema::dropIfExists('semesters');
    }
}
