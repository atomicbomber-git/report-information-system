<?php

use Illuminate\Database\Seeder;
use App\User;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Tuan Admin',
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'privilege' => 'administrator'
        ]);
    }
}
