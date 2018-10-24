<?php

use Illuminate\Database\Seeder;
use App\Teacher;

class HeadmasterAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(App\User::class)->create([
            'name' => 'Dra. Elly Utari Yuniar, M. Pd.',
            'username' => 'kepsek',
            'password' => bcrypt('kepsek'),
            'privilege' => 'headmaster'
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'teacher_id' => '195906071981032012',
            'active' => 1
        ]);
    }
}
