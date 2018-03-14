<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Student;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 40)->create()->each(function($user) {
            $user->student()->save( factory(Student::class)->make() );
        });
    }
}
