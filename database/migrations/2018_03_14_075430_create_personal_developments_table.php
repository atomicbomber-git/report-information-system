<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalDevelopmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_developments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('permitted_absence')->default(0); // Ketidakhadiran (izin)
            $table->integer('sickness_absence')->default(0); // Ketidakhadiran (sakit)
            $table->integer('unpermitted_absence')->default(0); // Ketidakhadiran (alpa)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_developments');
    }
}
