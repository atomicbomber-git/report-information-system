<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\RoomTerm;
use App\Term;
use App\Course;
use App\KnowledgeGrade;

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
            ->orderBy('term_end', 'desc')
            ->orderBY('even_odd')
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
            ->select(
                'rooms.name AS room_name',
                'courses.name AS course_name',
                'courses.id AS course_id', 'rooms.grade',
                DB::raw('COUNT(reports.id) AS report_count'),
                'room_terms.id'
            )
            ->join('room_terms', 'room_terms.id', '=', 'course_teachers.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->join('courses', 'courses.id', '=', 'course_teachers.course_id')
            ->leftJoin('reports', 'reports.room_term_id', '=', 'room_terms.id')
            ->where('room_terms.term_id', $term_id)
            ->where('room_terms.even_odd', $even_odd)
            ->where('course_teachers.teacher_id', $teacher_id)
            ->groupBy('room_terms.id', 'rooms.name', 'courses.name', 'courses.id', 'rooms.grade')
            ->get();

        $room_term_groups = $room_terms->groupBy('grade');
        // return $room_term_groups;

        return view('teacher_management.courses', [
            'room_term_groups' => $room_term_groups,
            'information' => $information
        ]);
    }

    public function courseDetail($term_id, $even_odd, $room_term_id, $course_id)
    {
        $teacher_id = auth()->user()->teacher->id;

        $knowledge_grade_groups = DB::table('knowledge_grades')
            ->select(
                DB::raw('DISTINCT(knowledge_grades.id)'),
                'knowledge_basic_competencies.name AS basic_competency_name',
                'knowledge_basic_competencies.id AS basic_competency_id',
                'users.name', 'first_exam', 'second_exam',
                'first_assignment', 'second_assignment', 'third_assignment',
                'first_remedial', 'second_remedial'
            )
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

        $knowledge_grade_groups = $knowledge_grade_groups->groupBy('basic_competency_id', 'basic_competency_name');

        // Term-related information
        $information = Term::find($term_id);
        $information->term_code = $information->code;
        $information->even_odd = $even_odd;
        $information->semester = RoomTerm::EVEN_ODD[$even_odd];

        $room = DB::table('room_terms')
            ->select('rooms.name')
            ->where('room_terms.id', $room_term_id)
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->first();
        
        $basic_competencies = DB::table('knowledge_basic_competencies')
            ->select('id', 'name')
            ->where('course_id', $course_id)
            ->get()
            ->keyBy('id');

        return view('teacher_management.course_detail', [
            'course' => Course::find($course_id),
            'knowledge_grade_groups' => $knowledge_grade_groups,
            'basic_competencies' => $basic_competencies,
            'information' => $information,
            'room' => $room,
        ]);
    }

    public function updateKnowledgeGrade() {
        // TODO add validation
        $data = request('data');

        DB::transaction(function() use($data) {
            foreach ($data as $knowledge_grade) {

                $id = $knowledge_grade['id'];
                unset($knowledge_grade['id']);
                
                DB::table('knowledge_grades')
                    ->where('id', $id)
                    ->update($knowledge_grade);
            }
        });
    }
}
