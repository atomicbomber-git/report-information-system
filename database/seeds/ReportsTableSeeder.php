<?php

use Illuminate\Database\Seeder;

use App\Student;
use App\RoomTerm;
use App\Report;
use App\Course;
use App\CourseReport;
use App\KnowledgeBasicCompetency;
use App\KnowledgeGrade;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class ReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $room_terms = DB::table('room_terms')
            ->select('room_terms.term_id', 'room_terms.id', 'rooms.grade', 'room_terms.even_odd', 'room_terms.room_id')
            ->join('rooms', 'rooms.id', 'room_terms.room_id')
            ->get()
            ->groupBy(['term_id', 'grade', 'room_id']);

        $student_grade_groups = DB::table('students')
            ->select('students.id', 'students.current_grade')
            ->where('active', 1)
            ->limit(40)
            ->get()
            ->groupBy('current_grade')
            ->map(function($student_grade_group) { return $student_grade_group->chunk(5); });

        $course_grade_groups = DB::table('courses')
            ->select('courses.id', 'courses.grade')
            ->get()
            ->groupBy('grade');

        foreach ($student_grade_groups as $grade => $student_groups) {
            // Only create reports if room terms from this grade exist
            $room_terms_per_room = $room_terms->first()->get($grade)->random();
            if ($room_terms_per_room) {
                foreach ($room_terms_per_room as $room_term) {
                    foreach ($student_groups as $student_group) {
                        foreach ($student_group as $student) {
                            // Report creation
                            $report = Report::create([
                                'room_term_id' => $room_term->id,
                                'student_id' => $student->id,
                                'social_attitude_description' => $faker->paragraph(2),
                                'spiritual_attitude_description' => $faker->paragraph(2),
                                'absence_sick' => $faker->randomNumber(1),
                                'absence_permit' => $faker->randomNumber(1),
                                'absence_unknown' => $faker->randomNumber(1)
                            ]);

                            // Create course reports for each report
                            if (isset($course_grade_groups[$grade])) {
                                foreach ($course_grade_groups[$grade] as $course) {
                                    $course_report = CourseReport::create([
                                        'course_id' => $course->id,
                                        'report_id' => $report->id,
                                        'mid_exam' => $faker->numberBetween(50, 100),
                                        'final_exam' => $faker->numberBetween(50, 100),
                                        'knowledge_description' => $faker->paragraph(4),
                                        'skill_description' => $faker->paragraph(4)
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
