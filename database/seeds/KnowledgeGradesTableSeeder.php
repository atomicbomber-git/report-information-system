<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\KnowledgeGrade;

class KnowledgeGradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $basic_competency_groups = DB::table('course_reports')
            ->join('knowledge_basic_competencies', 'knowledge_basic_competencies.course_id', '=', 'course_reports.course_id')
            ->select('knowledge_basic_competencies.id', 'course_reports.course_id', 'knowledge_basic_competencies.even_odd')
            ->groupBy('knowledge_basic_competencies.id','course_reports.course_id')
            ->get()
            ->groupBy('even_odd')
            ->map(function($group) { return $group->groupBy('course_id'); });

        $course_report_groups = DB::table('course_reports')
            ->select('course_reports.id', 'course_reports.course_id', 'room_terms.even_odd')
            ->join('courses', 'courses.id', '=', 'course_reports.course_id')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->join('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->get()
            ->groupBy('even_odd');

        foreach ($course_report_groups as $even_odd => $course_reports) {
            foreach ($course_reports as $course_report) {

                $basic_competencies = $basic_competency_groups[$even_odd][$course_report->course_id];
                
                foreach ($basic_competencies as $basic_competency) {
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
