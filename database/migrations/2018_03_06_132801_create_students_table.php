<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unique(); // Id dari tabel users
            $table->string('student_id')->unique()->index(); // Nomor induk
            $table->enum('sex', ['male', 'female']); // Jenis kelamin
            $table->string('birthplace'); // Tempat lahir
            $table->date('birthdate'); // Tanggal lahir
            $table->string('religion'); // Agama
            $table->string('alma_mater')->nullable(); // Sekolah asal
            $table->date('acceptance_date')->nullable(); // Sekolah asal
            $table->string('status_in_family')->nullable(); // Status dalam keluarga
            $table->integer('nth_child')->nullable(); // Anak ke-
            $table->string('address')->nullable(); // Alamat siswa
            $table->string('phone')->nullable(); // Nomor telefon siswa
            $table->string('father_name')->nullable(); // Nama ayah
            $table->string('mother_name')->nullable(); // Nama ibu
            $table->string('parents_address')->nullable(); // Alamat orang tua
            $table->string('parents_phone')->nullable(); // No telp orang tua
            $table->string('father_occupation')->nullable(); // Pekerjaan ayah
            $table->string('mother_occupation')->nullable(); // Pekerjaan ibu
            $table->string('guardian_name')->nullable(); // Nama wali
            $table->string('guardian_address')->nullable(); // Alamat wali
            $table->string('guardian_phone')->nullable(); // No telepon wali
            $table->string('guardian_occupation')->nullable(); // Pekerjaan wali
            
            $table->integer('current_grade')
                ->unsigned()->index()->nullable(); // Jenjang (7, 8, 9)

            $table->boolean('active')->default(1); // Status aktif / tidak aktif

            $table->timestamps();
        
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('students');
    }
}
