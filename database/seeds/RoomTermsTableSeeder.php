<?php

use Illuminate\Database\Seeder;

use App\Term;
use App\Room;
use App\RoomTerm;

class RoomTermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terms = Term::all();
        $rooms = Room::all();
        $room_terms = RoomTerm::all();

        foreach ($terms as $term) {
            foreach ($rooms as $room) {
                $term->rooms()->attach($room, ['even_odd' => 'odd']);
                $term->rooms()->attach($room, ['even_odd' => 'even']);
            }
        }
    }
}
