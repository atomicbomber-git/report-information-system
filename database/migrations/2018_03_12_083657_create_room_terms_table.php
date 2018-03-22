<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_terms', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('room_id')->unsigned(); // ID ruangan
            $table->integer('term_id')->unsigned()->index(); // ID tahun ajaran
            $table->enum('even_odd', ['even', 'odd']); // Semester genap / ganjil
            $table->integer('teacher_id')->unsigned()->nullable(); // ID wali ruangan dari tabel 'teachers'

            $table->foreign('room_id')->references('id')->on('rooms')
                ->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')
                ->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onDelete('cascade');;

            $table->unique(['room_id', 'term_id', 'even_odd']);

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
        Schema::dropIfExists('room_term');
    }
}
