<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CourseTeacherController extends Controller
{
    public function termIndex()
    {
        $terms = DB::table('room_terms')
            ->select('term_id AS id', 'even_odd', 'code')
            ->join('terms', 'terms.id', '=', 'room_terms.term_id')
            ->groupBy('term_id', 'even_odd', 'code')
            ->get();

        $grades = DB::table('rooms')
            ->select('rooms.grade')
            ->groupBy('rooms.grade')
            ->get()
            ->map(function ($room) { return $room->grade; });

        return view('course_teachers.terms', [
            'current_page' => 'course_teachers',
            'terms' => $terms,
            'grades' => $grades
        ]);
    }

    public function gradeIndex($term_id, $even_odd, $grade)
    {
        $courses = DB::table('course_teachers')
            ->select(
                'course_teachers.id',
                'courses.name AS course_name',
                'rooms.name AS room_name',
                'users.name AS teacher_name',
                'teachers.teacher_id AS teacher_code',
                'course_teachers.teacher_id'
            )
            ->join('room_terms', 'room_terms.id', '=', 'course_teachers.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->join('courses', 'courses.id', '=', 'course_teachers.course_id')
            ->leftJoin('teachers', 'teachers.id', '=', 'course_teachers.teacher_id')
            ->leftJoin('users', 'users.id', '=', 'teachers.user_id')
            ->where('room_terms.term_id', $term_id)
            ->where('room_terms.even_odd', $even_odd)
            ->where('courses.grade', $grade)
            ->orderBy('courses.name')
            ->orderBy('rooms.name')
            ->get()
            ->groupBy('course_name', 'course_teachers.id', 'course_name', 'room_name', 'teacher_name', 'teacher_code', 'course_teachers.teacher_id');

        $teachers = DB::table('teachers')
            ->select('teachers.id', 'teachers.teacher_id', 'users.name')
            ->join('users', 'users.id', '=','teachers.user_id')
            ->get();

        $empty_teacher = new \stdClass();
        $empty_teacher->id = NULL;
        $empty_teacher->teacher_id = '-';
        $empty_teacher->name = ' KOSONG ';
        
        $teachers->push($empty_teacher);

        $information = new \stdClass();
        $information->term = DB::table('terms')
            ->where('terms.id', $term_id)
            ->first();
        $information->even_odd = \App\RoomTerm::EVEN_ODD[$even_odd];
        $information->grade = $grade;

        return view('course_teachers.grade_index', [
            'information' => $information,
            'courses' => $courses,
            'teachers' => $teachers
        ]);
    }

    public function update()
    {
        $data = request('data');

        DB::transaction(function() use ($data) {
            foreach ($data as $record) {
                DB::table('course_teachers')
                    ->where('id', $record['id'])
                    ->update(['teacher_id' => $record['teacher_id']]);
            }
        });

    }
}
