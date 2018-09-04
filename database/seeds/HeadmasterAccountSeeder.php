<?php

use Illuminate\Database\Seeder;

class HeadmasterAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class)->create([
            'name' => 'Irene',
            'username' => 'kepsek',
            'password' => bcrypt('kepsek'),
            'privilege' => 'headmaster'
        ]);
    }
}
