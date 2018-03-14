<?php

use Illuminate\Database\Seeder;

use App\CourseReport;
use App\BasicCompetency;

class BasicCompetencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $course_reports = CourseReport::all();
        
        foreach ($course_reports as $course_report) {
            for ($i = 1; $i <= 10; $i++) {
                $basic_competency = factory(BasicCompetency::class)->make();
                $basic_competency['name'] = "KD $i";
                $course_report->basic_competencies()->save($basic_competency);
            }
        }
    }
}
