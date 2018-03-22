<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Teacher;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create()->each(function($user) {
            $user->privilege = 'teacher';
            $user->teacher()->save( factory(Teacher::class)->make() );
        });
    }
}
