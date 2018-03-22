<?php

use Illuminate\Database\Seeder;

use App\Teacher;
use App\RoomTerm;
use App\Course;
use App\CourseTeacher;

class CourseTeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teachers = Teacher::select('id')->get();

        $room_terms = RoomTerm::select('room_terms.id', 'rooms.grade')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->get()->groupBy('grade');

        $courses = Course::select('id', 'grade')
            ->get()->groupBy('grade');

        foreach ($room_terms as $grade => $grade_group) {
            foreach ($grade_group as $room_term) {
                if (isset($courses[$grade])) {
                    foreach ($courses[$grade] as $course) {
                        CourseTeacher::create([
                            'course_id' => $course->id,
                            'teacher_id' => $teachers->random()->id,
                            'room_term_id' => $room_term->id
                        ]);
                    }
                }
            }
        }
    }
}
