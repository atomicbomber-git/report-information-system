<?php

use Illuminate\Database\Seeder;
use App\SkillGrade;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class SkillGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $skill_score_types = ['PRAKTIK', 'PRODUK', 'PROYEK', 'PORTOFOLIO'];

        $basic_competency_groups = DB::table('course_reports')
            ->join('knowledge_basic_competencies', 'knowledge_basic_competencies.course_id', '=', 'course_reports.course_id')
            ->select('knowledge_basic_competencies.id', 'course_reports.course_id', 'knowledge_basic_competencies.even_odd')
            ->groupBy('knowledge_basic_competencies.id','course_reports.course_id')
            ->get()
            ->groupBy('even_odd', 'knowledge_basic_competencies.id', 'course_reports.course_id')
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

                    foreach ($skill_score_types as $type) {

                        SkillGrade::create([
                            'course_report_id' => $course_report->id,
                            'knowledge_basic_competency_id' => $basic_competency->id,
                            'type' => $type,
                            'score_1' => $faker->biasedNumberBetween(50, 100),
                            'score_2' => $faker->biasedNumberBetween(50, 100),
                            'score_3' => $faker->biasedNumberBetween(50, 100),
                            'score_4' => $faker->biasedNumberBetween(50, 100),
                            'score_5' => $faker->biasedNumberBetween(50, 100),
                            'score_6' => $faker->biasedNumberBetween(50, 100)
                        ]);

                    }
                }
            }
        }
    }
}
