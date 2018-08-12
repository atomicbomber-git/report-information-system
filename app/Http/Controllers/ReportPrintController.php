<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use DB;

class ReportPrintController extends Controller
{
    public function printReport(Report $report)
    {
        $course_report_group_query = DB::table('knowledge_grades')
            ->select(
                'course_reports.id AS id',
                'courses.id AS course_id',
                'courses.name',
                'courses.group',
                'course_reports.mid_exam',
                'course_reports.final_exam',
                'course_reports.knowledge_description',
                'course_reports.skill_description',
                DB::raw('ROUND(AVG(GREATEST(((first_assignment + second_assignment + third_assignment + first_exam + second_exam ) / 5 ), first_remedial, second_remedial))) AS knowledge_grade')
            )
            ->join('course_reports', 'course_reports.id', '=', 'knowledge_grades.course_report_id')
            ->join('courses', 'courses.id', '=', 'course_reports.course_id')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->where('students.id', $report->student_id)
            // ->where('course_reports.report_id', $report->id)
            ->groupBy(
                'course_reports.course_id',
                'courses.name',
                'course_reports.id',
                'courses.group',
                'course_reports.mid_exam',
                'course_reports.final_exam',
                'course_reports.knowledge_description',
                'course_reports.skill_description'
            );
        
        if ($report->room_term->getOriginal('even_odd') == 'odd') {
            $course_report_group_query = $course_report_group_query
                ->where('reports.room_term_id', $report->room_term_id);
        }

        $course_report_groups = $course_report_group_query
            ->get()
            ->groupBy('group');
            
        $skill_grade_groups = DB::table('skill_grades')
            ->select(
                'course_reports.course_id',
                DB::raw('ROUND(AVG(GREATEST(score_1, score_2, score_3, score_4, score_5, score_6))) AS grade')
            )
            ->rightJoin('course_reports', 'course_reports.id', '=', 'skill_grades.course_report_id')
            ->rightJoin('courses', 'courses.id', '=', 'course_reports.course_id')
            ->where('course_reports.report_id', $report->id)
            ->groupBy('course_reports.course_id')
            ->get()
            ->mapWithKeys(function ($item) { return [$item->course_id => $item->grade]; });
        
        // dd($skill_grade_groups);

        return view('teacher_management.print_report', [
            'report' => $report,
            'course_report_groups' => $course_report_groups,
            'skill_grade_groups' => $skill_grade_groups
        ]);
    }

    public function printReportCover(Report $report)
    {
        $first_class = DB::table('rooms')
            ->select('rooms.name')
            ->join('room_terms', 'room_terms.room_id', '=', 'rooms.id')
            ->join('terms', 'terms.id', '=', 'room_terms.term_id')
            ->join('reports', 'reports.room_term_id', '=', 'room_terms.id')
            ->where('reports.student_id', $report->student->id)
            ->orderBy('terms.term_start')
            ->value('name');

        return view('teacher_management.print_cover', [
            'report' => $report,
            'first_class' => $first_class
        ]);
    }
}
