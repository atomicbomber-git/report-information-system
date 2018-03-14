<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');

            $table->year('term_start'); // Tahun mulainya tahun ajaran
            $table->year('term_end'); // Tahun berakhirnya tahun ajaran
            $table->string('code')->unique(); // Kode tahun ajaran

            $table->unique(['term_start', 'term_end']);

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
        Schema::dropIfExists('terms');
    }
}
