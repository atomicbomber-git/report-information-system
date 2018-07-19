<?php

use Illuminate\Database\Seeder;

use App\SkillGrade;
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
        $skill_score_types = ['PRAKTIK', 'PRODUK', 'PROYEK', 'PORTOFOLIO'];

        $basic_competency_groups = DB::table('course_reports')
            ->join('knowledge_basic_competencies', 'knowledge_basic_competencies.course_id', '=', 'course_reports.course_id')
            ->select('knowledge_basic_competencies.id', 'course_reports.course_id')
            ->groupBy('knowledge_basic_competencies.id','course_reports.course_id')
            ->get()
            ->groupBy('course_id');

        $course_reports = DB::table('course_reports')
            ->select('id', 'course_id')
            ->get();

        foreach ($course_reports as $course_report) {
            $basic_competencies = $basic_competency_groups[$course_report->course_id];

            foreach ($basic_competencies as $basic_competency) {
                foreach ($skill_score_types as $type) {

                    SkillGrade::create([
                        'course_report_id' => $course_report->id,
                        'knowledge_basic_competency_id' => $basic_competency->id,
                        'type' => $type
                    ]);

                }
            }
        }
    }
}
