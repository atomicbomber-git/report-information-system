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
        // Instantiate fake data generator
        $faker = \Faker\Factory::create();

        $room_terms = RoomTerm::all()
            ->groupBy('grade');
        
        $students = Student::all()
            ->groupBy('current_grade')
            ->map(function($grade) { return $grade->chunk(40); });

        $courses = Course::where('active', 1)
            ->get()
            ->groupBy('grade');
        
        // All knowledge basic competencies of each respective courses
        $knowledge_basic_competencies = KnowledgeBasicCompetency::select('knowledge_basic_competencies.id', 'courses.id AS course_id', 'courses.grade AS grade')
            ->join('courses', 'courses.id', '=', 'knowledge_basic_competencies.course_id')
            ->where('active', 1)
            ->get()
            ->groupBy('grade')
            ->map(function ($grade_group) { return $grade_group->groupBy('course_id'); });

        DB::transaction(function() use ($students, $faker, $room_terms, $courses, $knowledge_basic_competencies) {
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
                                    $course_report = CourseReport::create([
                                        'course_id' => $course->id,
                                        'report_id' => $report->id,
                                        'mid_exam' => $faker->numberBetween(50, 100),
                                        'final_exam' => $faker->numberBetween(50, 100),
                                        'knowledge_description' => $faker->paragraph(4),
                                        'skill_description' => $faker->paragraph(4)
                                    ]);
                                    
                                    // Fill knowledge_grades table
                                    foreach ($knowledge_basic_competencies[$grade][$course->id] as $basic_competency) {
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
        });
    }
}
