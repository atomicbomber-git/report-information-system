<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('teacher_id')->index(); // NIP
            $table->integer('user_id')->unsigned()->unique(); // Id dari tabel users

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            
            $table->timestamps();

            $table->boolean('active')->default(1); // Status aktif / non-aktif
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
