<?php

use Illuminate\Database\Seeder;

use App\Student;
use App\RoomTerm;
use App\Report;
use App\Course;
use App\CourseReport;

class ReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $room_terms = RoomTerm::all()
            ->groupBy('grade');

        $students = Student::all()
            ->groupBy('current_grade')
            ->map(function($grade) { return $grade->chunk(40); });

        $courses = Course::where('active', 1)
            ->get()
            ->groupBy('grade');

        foreach ($students as $grade => $grade_group) {
            foreach ($grade_group as $count => $chunk) {
                foreach ($chunk as $student) {
                    if (isset($room_terms[$grade])) {

                        // Fill reports table
                        $report = Report::create([
                            'room_term_id' => $room_terms[$grade][$count]->id,
                            'student_id' => $student->id
                        ]);
                        
                        // Fill course_reports table
                        if (isset($courses[$grade])) {
                            foreach ($courses[$grade] as $course) {
                                CourseReport::create([
                                    'course_id' => $course->id,
                                    'report_id' => $report->id
                                ]);
                            }
                        }
                    }
                }
            }
        }

        
    }
}
