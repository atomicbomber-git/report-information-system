<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use DB;

class StudentAccessController extends Controller
{
    public function terms()
    {
        $terms = DB::table('reports')
            ->select('terms.id', 'terms.code', 'room_terms.even_odd', 'reports.id AS report_id')
            ->join('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->join('terms', 'terms.id', '=', 'room_terms.term_id')
            ->where('reports.student_id', auth()->user()->student->id)
            ->orderBy('term_start', 'desc')
            ->get()
            ->groupBy('code')
            ->map(function($term) {
                $term = $term->keyBy('even_odd');
                return $term;
            });

        return view('student_access.terms', compact('terms'));
    }

    public function report(Report $report)
    {
        $course_groups = DB::table('courses')
            ->select('courses.id', 'courses.name', 'courses.group')
            ->join('course_reports', 'course_reports.course_id', '=', 'courses.id')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('reports.id', $report->id)
            ->groupBy('courses.id', 'courses.name', 'courses.group')
            ->get()
            ->groupBy('group');

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

        foreach ($course_groups as $group) {
            foreach ($group as $course) {
                $course->knowledge_grade = $knowledge_grades[$course->id] ?? 0;
                $course->skill_grade = $skill_grades[$course->id] ?? 0;
                $course->descriptions = $descriptions[$course->id] ?? '';
            }
        }

        return view('student_access.report', compact('report', 'course_groups', 'extracurriculars'));
    }
}
