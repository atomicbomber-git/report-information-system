<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\User;
use DB;

class ReportPrintController extends Controller
{
    public function printReport(Report $report)
    {
        $headmaster = User::where('privilege', 'HEADMASTER')->with('teacher:id,user_id,teacher_id')->first();

        $course_groups = DB::table('courses')
            ->select('courses.id', 'courses.name', 'courses.group')
            ->join('course_reports', 'course_reports.course_id', '=', 'courses.id')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('reports.id', $report->id)
            ->groupBy('courses.id', 'courses.name', 'courses.group')
            ->get()
            ->groupBy('group');

        $knowledge_grades = collect(DB::table('knowledge_grades_summary')
                ->select('knowledge_grades_summary.id', 'knowledge_grades_summary.course_id', DB::raw('((AVG(grade) + final_exam + mid_exam) / 3)  AS knowledge_grade'), 'course_report_id')
                ->join('course_reports', 'course_reports.id', '=', 'knowledge_grades_summary.course_report_id')
                ->join('reports', 'reports.id', '=', 'course_reports.report_id')
                ->where('reports.student_id', $report->student_id)
                ->when($report->room_term->getOriginal('even_odd') == 'odd', function ($query) use($report) {
                    $query->where('reports.room_term_id', $report->room_term_id);
                })
                ->groupBy('course_report_id', 'mid_exam', 'final_exam', 'knowledge_grades_summary.course_id')
                ->get()
                ->mapWithKeys(function ($item) { return [$item->course_id => $item->knowledge_grade]; }));
                
        $skill_grades = DB::table('skill_grades_summary')
            ->select(
                'course_reports.course_id',
                'grade'
            )
            ->rightJoin('course_reports', 'course_reports.id', '=', 'skill_grades_summary.course_report_id')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('reports.student_id', $report->student_id)
            ->when($report->room_term->getOriginal('even_odd') == 'odd', function ($query) use($report) {
                $query->where('reports.room_term_id', $report->room_term_id);
            })
            ->get()
            ->mapWithKeys(function ($item) { return [$item->course_id => $item->grade]; });

        $descriptions = DB::table('course_reports')
            ->select('course_reports.course_id', 'course_reports.knowledge_description', 'course_reports.skill_description')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('reports.id', $report->id)
            ->groupBy('course_reports.course_id', 'course_reports.knowledge_description', 'course_reports.skill_description')
            ->get()
            ->keyBy('course_id');

        $extracurriculars = DB::table('extracurricular_reports')
            ->select('extracurriculars.name', 'extracurricular_reports.score')
            ->join('extracurriculars', 'extracurriculars.id', '=', 'extracurricular_reports.extracurricular_id')
            ->where('extracurricular_reports.report_id', $report->id)
            ->orderBy('extracurriculars.name')
            ->get();
        
        return view(
            'teacher_management.print_report',
            compact('report', 'course_groups', 'knowledge_grades', 'skill_grades', 'descriptions', 'extracurriculars', 'headmaster')
        );
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

        $headmaster = User::where('privilege', 'HEADMASTER')->with('teacher:id,user_id,teacher_id')->first();

        return view('teacher_management.print_cover', [
            'report' => $report,
            'first_class' => $first_class,
            'headmaster' => $headmaster
        ]);
    }
}
