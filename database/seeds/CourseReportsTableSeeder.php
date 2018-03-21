<?php

use Illuminate\Database\Seeder;

use App\Course;
use App\Report;
use App\CourseReport;

class CourseReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reports = Report::all();
        $courses = Course::all();

        foreach ($reports as $report) {
            foreach ($courses as $course) {
                CourseReport::create([
                    'course_id' => $course->id,
                    'report_id' => $report->id
                ]);
            }
        }
    }
}
