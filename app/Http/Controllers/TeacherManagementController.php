<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\RoomTerm;
use App\Term;
use App\Course;
use App\KnowledgeGrade;
use App\SkillGrade;

class TeacherManagementController extends Controller
{
    public function terms()
    {
        // return auth()->user()->teacher;

        $teacher_id = auth()->user()->teacher->id;
        $terms = DB::table('course_teachers')
            ->select('terms.id', 'terms.code', 'room_terms.even_odd')
            ->join('room_terms', 'room_terms.id', '=', 'course_teachers.room_term_id')
            //->join('room_terms AS rx', 'rx.teacher_id', '=', 'course_teachers.teacher_id')
            ->join('terms', 'terms.id', '=', 'room_terms.term_id')
            ->groupBy('terms.id', 'terms.code', 'room_terms.even_odd')
            ->orderBy('term_end', 'desc')
            ->orderBY('room_terms.even_odd')
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

        $managed_room_terms = DB::table('room_terms')
            ->select('room_terms.id', 'rooms.name', 'room_terms.even_odd')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term_id)
            ->where('room_terms.even_odd', $even_odd)
            ->where('room_terms.teacher_id', $teacher_id)
            ->get();
        
        return view('teacher_management.courses', [
            'room_term_groups' => $room_term_groups,
            'managed_room_terms' => $managed_room_terms,
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
            ->orderBy('users.name')
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
        $information->room_term_id = $room_term_id;
        $information->course_id = $course_id;

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
            'room' => $room
        ]);
    }

    public function courseExams($term_id, $even_odd, $room_term_id, $course_id)
    {
        $information = Term::find($term_id);
        $information->term_code = $information->code;
        $information->even_odd = $even_odd;
        $information->semester = RoomTerm::EVEN_ODD[$even_odd];
        $information->room_term_id = $room_term_id;
        $information->course_id = $course_id;
        
        $course_reports = DB::table('course_reports')
            ->select('course_reports.id', 'users.name', 'mid_exam', 'final_exam', 'skill_description')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('course_reports.course_id', '=', $course_id)
            ->where('reports.room_term_id', '=', $room_term_id)
            ->orderBy('users.name')
            ->get();
        
        return view('teacher_management.exams', [
            'information' => $information,
            'course_reports' => $course_reports,
            'course' => Course::find($course_id),
            'room' => RoomTerm::where('room_terms.id', $room_term_id)->join('rooms', 'rooms.id', '=', 'room_terms.room_id')->first()
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

    public function updateCourseReport()
    {
        // TODO add validation
        $data = request('data');

        DB::transaction(function() use($data) {
            foreach ($data as $course_report) {
                $id = $course_report['id'];
                unset($course_report['id']);

                DB::table('course_reports')
                    ->where('id', $id)
                    ->update($course_report);
            }
        });
    }

    public function roomTerm($room_term_id)
    {
        $information =  DB::table('room_terms')
            ->join('terms', 'terms.id', '=', 'room_terms.term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.id', $room_term_id)
            ->first();

        $information->term_code = $information->code;
        $information->room_name = $information->name;
        $information->semester = RoomTerm::EVEN_ODD[$information->even_odd];

        $reports = DB::table('room_terms')
            ->select('reports.id', 'users.name')
            ->join('reports', 'reports.room_term_id', '=', 'room_terms.id')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('reports.room_term_id', $room_term_id)
            ->orderBy('users.name')
            ->get();

        return view('teacher_management.room_terms', [
            'information' => $information,
            'reports' => $reports
        ]);
    }

    public function printReport($report_id)
    {
        $course_reports = KnowledgeGrade
            ::select(
                'course_reports.id AS id',
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
            ->where('course_reports.report_id', $report_id)
            ->groupBy(
                'course_reports.course_id',
                'courses.name',
                'course_reports.id',
                'courses.group',
                'course_reports.mid_exam',
                'course_reports.final_exam',
                'course_reports.knowledge_description',
                'course_reports.skill_description'
            )
            ->get();
        
        $course_report_groups = $course_reports->groupBy('group');
        
        return view('teacher_management.print_report', [
            'course_report_groups' => $course_report_groups
        ]);
    }

    public function skillDetail($term_id, $even_odd, $room_term_id, $course_id)
    {
        // Term-related information
        $information = Term::find($term_id);
        $information->term_code = $information->code;
        $information->even_odd = $even_odd;
        $information->semester = RoomTerm::EVEN_ODD[$even_odd];
        $information->room_term_id = $room_term_id;
        $information->course_id = $course_id;

        $skill_grade_groups = DB::table('reports')
            ->select('users.name AS student_name', 'knowledge_basic_competencies.id AS basic_competency_id',
                'knowledge_basic_competencies.name AS basic_competency_name', 'skill_grades.course_report_id', 'skill_grades.type',
                'skill_grades.id AS skill_grade_id',
                'skill_grades.knowledge_basic_competency_id', 'score_1', 'score_2', 'score_3', 'score_4', 'score_5', 'score_6'
                )
            ->join('course_reports', 'course_reports.report_id', '=', 'reports.id')
            ->join('skill_grades', 'skill_grades.course_report_id', '=', 'course_reports.id')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('knowledge_basic_competencies', 'knowledge_basic_competencies.id', '=', 'skill_grades.knowledge_basic_competency_id')
            ->where('reports.room_term_id', $room_term_id)
            ->where('course_reports.course_id', $course_id)
            ->get()
            ->groupBy('basic_competency_name');

        $skill_grade_groups = $skill_grade_groups->map(function ($group) {
            return $group->groupBy('student_name');
        });

        $course = DB::table('courses')->where('id', $course_id)->first();
        
        $room = DB::table('room_terms')
            ->select('rooms.name')
            ->where('room_terms.id', $room_term_id)
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->first();

        return view('teacher_management.skill_detail', [
            'information' => $information,
            'course' => $course,
            'room' => $room,
            'skill_grade_groups' => $skill_grade_groups
        ]);
    }

    public function updateSkillGrade()
    {
        DB::transaction(function() {
            foreach (request('data') as $key => $record) {
                DB::table('skill_grades')
                    ->where('id', $key)
                    ->update($record);
            }
        });
    }
}
