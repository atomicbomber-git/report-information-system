<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CourseReport;

class CourseReportController extends Controller
{
    public function detail($course_report_id)
    {
        $course_report = CourseReport
            ::select('courses.name', 'course_reports.report_id')
            ->where('course_reports.id', $course_report_id)
            ->join('courses', 'courses.id', '=', 'course_reports.course_id')
            ->first();

        $course_report->id = $course_report_id;

        $knowledge_grades = CourseReport
            ::select(
                'knowledge_grades.id',
                'knowledge_basic_competencies.name',
                'first_assignment',
                'second_assignment',
                'third_assignment',
                'first_exam',
                'second_exam',
                'first_remedial',
                'second_remedial'
            )
            ->where('course_reports.id', $course_report_id)
            ->join('knowledge_grades', 'knowledge_grades.course_report_id', '=', 'course_reports.id')
            ->join('knowledge_basic_competencies', 'knowledge_basic_competencies.id', '=', 'knowledge_grades.knowledge_basic_competency_id')
            ->get();

        return view('course_reports.detail', [
            'course_report' => $course_report,
            'knowledge_grades' => $knowledge_grades
        ]);
     }
}
