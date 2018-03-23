<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\RoomTerm;
use App\Term;

class TeacherManagementController extends Controller
{
    public function terms()
    {
        $teacher_id = auth()->user()->teacher->id;
        $terms = DB::table('course_teachers')
            ->select('terms.id', 'terms.code', 'even_odd')
            ->join('room_terms', 'room_terms.id', '=', 'course_teachers.room_term_id')
            ->join('terms', 'terms.id', '=', 'room_terms.term_id')
            ->groupBy('terms.id', 'terms.code', 'even_odd')
            ->where('course_teachers.teacher_id', $teacher_id)
            ->get();

        $terms = $terms->map(function($term) {
            $term->semester = RoomTerm::EVEN_ODD[$term->even_odd];
            return $term;
        });

        return view('teacher_management.terms', [
            'terms' => $terms
        ]);
    }

    public function courses($term_id, $even_odd)
    {
        $teacher_id = auth()->user()->teacher->id;
        
        $information = Term::find($term_id);
        $information->term_code = $information->code;
        $information->even_odd = $even_odd;
        $information->semester = RoomTerm::EVEN_ODD[$even_odd];

        $room_terms = DB::table('course_teachers')
            ->select('room_terms.id', 'courses.name AS course_name', 'rooms.name AS room_name', 'room_terms.id AS room_term_id', 'rooms.grade', 'courses.id AS course_id')
            ->join('room_terms', 'room_terms.id', '=', 'course_teachers.room_term_id')
            ->join('reports', 'reports.room_term_id', '=', 'room_terms.id')
            ->join('course_reports', 'course_reports.report_id', '=', 'reports.id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->join('courses', 'courses.id', '=', 'course_teachers.course_id')
            ->where('course_teachers.teacher_id', $teacher_id)
            ->where('room_terms.term_id', $term_id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('room_terms.id', 'courses.name', 'rooms.name', 'rooms.grade','courses.id')
            ->get();

        $room_term_groups = $room_terms->groupBy('grade');

        return view('teacher_management.courses', [
            'room_term_groups' => $room_term_groups,
            'information' => $information
        ]);
    }

    public function courseDetail($term_id, $even_odd, $room_term_id, $course_id)
    {
        $teacher_id = auth()->user()->teacher->id;

        $knowledge_grade_groups = DB::table('knowledge_grades')
            ->select('knowledge_basic_competencies.id AS basic_competency_id', 'users.name', 'first_exam', 'second_exam', 'first_assignment', 'second_assignment', 'third_assignment', 'first_remedial', 'second_remedial')
            ->join('course_reports', 'course_reports.id', '=', 'knowledge_grades.course_report_id')
            ->join('knowledge_basic_competencies', 'knowledge_basic_competencies.id', '=', 'knowledge_grades.knowledge_basic_competency_id')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('course_teachers', 'course_teachers.room_term_id', '=', 'reports.room_term_id')
            ->where('reports.room_term_id', $room_term_id)
            ->where('course_teachers.teacher_id', $teacher_id)
            ->where('course_reports.course_id', $course_id)
            ->get();

        $knowledge_grade_groups = $knowledge_grade_groups->groupBy('basic_competency_id');

        return $knowledge_grade_groups;
    }
}
