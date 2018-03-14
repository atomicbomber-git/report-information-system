<?php

use Illuminate\Database\Seeder;

use App\Student;
use App\RoomTerm;

class ReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $room_terms = RoomTerm::all();
        $student_groups = Student::all()->chunk(20);
        
        foreach ($student_groups as $i => $student_group) {
            foreach ($student_group as $student) {
                $room_terms[$i]->students()->attach($student);
            }
        }
    }
}
