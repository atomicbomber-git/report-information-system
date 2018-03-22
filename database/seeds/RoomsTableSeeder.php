<?php

use Illuminate\Database\Seeder;
use App\Room;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_rooms = [
            ['name' => 'VIIA', 'grade' => 7],
            ['name' => 'VIIB', 'grade' => 7],
            ['name' => 'VIIC', 'grade' => 7],
            ['name' => 'VIID', 'grade' => 7],
            ['name' => 'VIIE', 'grade' => 7],
            ['name' => 'VIIF', 'grade' => 7],
            ['name' => 'VIIG', 'grade' => 7],
            ['name' => 'VIIIA', 'grade' => 8],
            ['name' => 'VIIIB', 'grade' => 8],
            ['name' => 'VIIIC', 'grade' => 8],
            ['name' => 'VIIID', 'grade' => 8],
            ['name' => 'VIIIE', 'grade' => 8],
            ['name' => 'VIIIF', 'grade' => 8],
            ['name' => 'VIIIG', 'grade' => 8],
            ['name' => 'IXA', 'grade' => 9],
            ['name' => 'IXB', 'grade' => 9],
            ['name' => 'IXC', 'grade' => 9],
            ['name' => 'IXD', 'grade' => 9],
            ['name' => 'IXE', 'grade' => 9],
            ['name' => 'IXF', 'grade' => 9],
            ['name' => 'IXG', 'grade' => 9]
        ];

        foreach ($default_rooms as $room) {
            Room::create($room);
        }
    }
}
