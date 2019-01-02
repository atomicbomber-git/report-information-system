<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteUnusedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP TABLE IF EXISTS social_aspects");
        DB::statement("DROP TABLE IF EXISTS spiritual_aspects");
        DB::statement("DROP TABLE IF EXISTS skill_aspects");
        DB::statement("DROP TABLE IF EXISTS personal_developments");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
