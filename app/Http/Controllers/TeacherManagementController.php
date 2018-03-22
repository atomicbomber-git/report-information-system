<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\RoomTerm;

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

        $room_terms = DB::table('course_teachers')
            ->select('courses.name', 'rooms.name AS room_name', 'room_terms.id AS room_term_id', 'rooms.grade')
            ->join('room_terms', 'room_terms.id', '=', 'course_teachers.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->join('courses', 'courses.id', '=', 'course_teachers.course_id')
            ->where('course_teachers.teacher_id', $teacher_id)
            ->where('room_terms.term_id', $term_id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('room_terms.id', 'courses.name', 'rooms.name', 'rooms.grade')
            ->get();

        return $room_terms;

        return view('teacher_management.courses');
    }

    public function courseDetail($term_id, $course_report_id)
    {

    }
}
