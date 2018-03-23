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

        $room_term_grade_groups = DB::table('room_terms')
            ->select('room_terms.id', 'rooms.grade')
            ->join('rooms', 'rooms.id', 'room_terms.room_id')
            ->get()
            ->groupBy('grade');

        $student_grade_groups = DB::table('students')
            ->select('students.id', 'students.current_grade')
            ->where('active', 1)
            ->get()
            ->groupBy('current_grade')
            ->map(function($student_grade_group) { return $student_grade_group->chunk(5); });

        $course_grade_groups = DB::table('courses')
            ->select('courses.id', 'courses.grade')
            ->where('active', 1)
            ->get()
            ->groupBy('grade');

        $knowledge_basic_competencies = DB::table('knowledge_basic_competencies')
            ->select('knowledge_basic_competencies.id', 'courses.id AS course_id', 'courses.grade AS grade')
            ->join('courses', 'courses.id', '=', 'knowledge_basic_competencies.course_id')
            ->where('courses.active', 1)
            ->get()
            ->groupBy('course_id');

        foreach ($student_grade_groups as $grade => $student_groups) {
            // Only create reports if room terms from this grade exist
            if (isset($room_term_grade_groups[$grade])) {
                foreach ($student_groups as $student_group) {

                    // Pick a random room term
                    $room_term_id = $room_term_grade_groups[$grade]->random()->id;

                    foreach ($student_group as $student) {
                        // Report creation
                        $report = Report::create([
                            'room_term_id' => $room_term_id,
                            'student_id' => $student->id
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

                                if (isset($knowledge_basic_competencies[$course_report->course_id])) {
                                    foreach ($knowledge_basic_competencies[$course_report->course_id] as $basic_competency) {
                                        KnowledgeGrade::create([
                                            'course_report_id' => $course_report->id,
                                            'knowledge_basic_competency_id' => $basic_competency->id,
                                            'first_assignment' => $faker->biasedNumberBetween(50, 100),
                                            'second_assignment' => $faker->biasedNumberBetween(50, 100),
                                            'third_assignment' => $faker->biasedNumberBetween(50, 100),
                                            'first_exam' => $faker->biasedNumberBetween(50, 100),
                                            'second_exam' => $faker->biasedNumberBetween(50, 100)
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
}
