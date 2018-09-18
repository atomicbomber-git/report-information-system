<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Term;
use App\RoomTerm;
use App\Student;
use App\KnowledgeGradeSummary;
use App\SkillGradeSummary;
use DB;

class HeadmasterAccessController extends Controller
{
    public function terms()
    {
        $terms = DB::table('terms')
            ->select('code', 'id')
            ->get();

        return view('headmaster_access.terms', compact('terms'));
    }

    public function roomTerms(Term $term, $even_odd)
    {
        $room_terms =  DB::table('room_terms')
            ->select('room_terms.id', 'rooms.name AS room_name')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->get();

        return view('headmaster_access.room_terms', compact('term', 'room_terms', 'even_odd'));
    }

    public function roomTerm(RoomTerm $room_term) {
        $room_term->load([
            'reports:id,room_term_id,student_id',
            'reports.student:id,user_id,student_id',
            'reports.student.user:id,name'
        ]);

        $even_odd = $room_term->getOriginal('even_odd');

        $reports = $room_term->reports->map(function($report) {
            return [
                'student_id' => $report->student->id,
                'student_name' => $report->student->user->name,
                'student_code' => $report->student->student_id
            ];
        })->sortBy('student_name');

        $knowledge_grades = KnowledgeGradeSummary::query()
            ->select('student_id', DB::raw('AVG(grade) AS grade'))
            ->when($even_odd == 'odd',
                function ($query) use ($room_term) {
                    $query->where('room_term_id', $room_term->id);
                    $query->groupBy('report_id');
                },
                function ($query) use($room_term) {
                    $query->where('room_id', $room_term->room->id);
                    $query->groupBy('student_id');
                }
            )
            ->get()
            ->mapWithKeys(function ($grade) { return [$grade->student_id => $grade->grade]; });

        $skill_grades = SkillGradeSummary::query()
            ->select('student_id', DB::raw('AVG(grade) AS grade'))
            ->when($even_odd == 'odd',
                function ($query) use ($room_term) {
                    $query->where('room_term_id', $room_term->id);
                    $query->groupBy('report_id', 'student_id');
                },
                function ($query) use($room_term) {
                    $query->where('room_id', $room_term->room->id);
                    $query->groupBy('student_id');
                }
            )
            ->get()
            ->mapWithKeys(function ($grade) { return [$grade->student_id => $grade->grade]; });

        return view('headmaster_access.room_term', compact('room_term', 'reports', 'knowledge_grades', 'skill_grades'));
    }

    public function teachers(Term $term, $even_odd)
    {
        $teachers = DB::table('teachers')
            ->select(
                'teachers.teacher_id', 'users.name', 'teachers.active',
                DB::raw('GROUP_CONCAT(DISTINCT courses.name ORDER BY courses.name SEPARATOR \', \') AS courses')
            )
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->join('course_teachers', 'course_teachers.teacher_id', '=', 'teachers.id')
            ->join('courses', 'courses.id', '=', 'course_teachers.course_id')
            ->join('room_terms', 'room_terms.id', '=', 'course_teachers.room_term_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('teachers.teacher_id', 'users.name', 'teachers.active')
            ->get();

        $teacher_classes = DB::table('teachers')
            ->select(
                'teachers.teacher_id', 'users.name',
                DB::raw('GROUP_CONCAT(rooms.name ORDER BY rooms.grade, rooms.name SEPARATOR \', \') AS classes')
            )
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->join('room_terms', 'room_terms.teacher_id', '=', 'teachers.id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('teachers.teacher_id', 'users.name')
            ->get()
            ->keyBy('teacher_id');
        
        return view('headmaster_access.teachers', compact('term', 'teachers', 'teacher_classes', 'even_odd'));
    }

    public function students()
    {
        $students = DB::table('students')
            ->select('students.id', 'student_id', 'name', 'sex', 'current_grade')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('active', 1)
            ->orderBy('students.current_grade')
            ->orderBy('users.name')
            ->get();

        return view('headmaster_access.students', compact('students'));
    }

    public function student(Student $student)
    {
        return view('headmaster_access.student', compact('student'));
    }
}
