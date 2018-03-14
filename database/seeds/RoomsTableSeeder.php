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
            'VIIA', 'VIIB', 'VIIC', 'VIID', 'VIIE', 'VIIF', 'VIIG',
            'VIIIA', 'VIIIB', 'VIIIC', 'VIIID', 'VIIIE', 'VIIIF', 'VIIIG',
            'IXA', 'IXB', 'IXC', 'IXD', 'IXE', 'IXF', 'IXG'
        ];

        foreach ($default_rooms as $room) {
            Room::create(['name' => $room]);
        }
    }
}
