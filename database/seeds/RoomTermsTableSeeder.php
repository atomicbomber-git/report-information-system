<?php

use Illuminate\Database\Seeder;

use App\Term;
use App\Room;
use App\Teacher;
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
        $teachers = Teacher::select('id')->get();
        $teacher_count = $teachers->count();

        $i = 0;
        foreach ($terms as $term) {
            foreach ($rooms as $room) {
                RoomTerm::create([
                    'term_id' => $term->id,
                    'room_id' => $room->id,
                    'even_odd' => 'odd',
                    'teacher_id' => $teachers[$i % $teacher_count]->id
                ]);

                RoomTerm::create([
                    'term_id' => $term->id,
                    'room_id' => $room->id,
                    'even_odd' => 'even',
                    'teacher_id' => $teachers[$i % $teacher_count]->id
                ]);

                ++$i;
            }
        }
    }
}
