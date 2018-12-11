<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeScoreFieldsToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skill_grades', function (Blueprint $table) {
            $table->float('score_1')->nullable()->change(); // Nilai 1
            $table->float('score_2')->nullable()->change(); // Nilai 2
            $table->float('score_3')->nullable()->change(); // Nilai 3
            $table->float('score_4')->nullable()->change(); // Nilai 4
            $table->float('score_5')->nullable()->change(); // Nilai 5
            $table->float('score_6')->nullable()->change(); // Nilai 6
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skill_grades', function (Blueprint $table) {
            $table->float('score_1')->default(0)->change(); // Nilai 1
            $table->float('score_2')->default(0)->change(); // Nilai 2
            $table->float('score_3')->default(0)->change(); // Nilai 3
            $table->float('score_4')->default(0)->change(); // Nilai 4
            $table->float('score_5')->default(0)->change(); // Nilai 5
            $table->float('score_6')->default(0)->change(); // Nilai 6
        });
    }
}
