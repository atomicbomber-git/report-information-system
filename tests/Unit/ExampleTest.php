<?php

namespace Tests\Unit;

use Tests\TestCase;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $test = DB::table('skill_grades')
            ->select('course_reports.id', 'skill_grades.type')
            ->join('knowledge_basic_competencies', 'knowledge_basic_competencies.id', '=', 'skill_grades.knowledge_basic_competency_id')
            ->join('course_reports', 'course_reports.id', '=', 'skill_grades.course_report_id')
            ->where('knowledge_basic_competencies.course_id', 3)
            ->groupBy('course_reports.id', 'skill_grades.type')
            ->get()
            ->mapToGroups(function ($item) {
                return [$item->id => $item->type];
            });

        eval(\Psy\sh());
    }
}
