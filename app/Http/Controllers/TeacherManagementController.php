<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\RoomTerm;
use App\Term;
use App\Course;
use App\Report;
use App\KnowledgeGrade;
use App\SkillGrade;

class TeacherManagementController extends Controller
{
    public function terms()
    {
        $teacher_id = auth()->user()->teacher->id;
        
        $course_teachers_query = DB::table('teachers')
            ->select('terms.id', 'terms.code', 'room_terms.even_odd', 'terms.term_end')
            ->join('course_teachers', 'course_teachers.teacher_id', '=', 'teachers.id')
            ->join('room_terms', 'room_terms.id', '=', 'course_teachers.room_term_id')
            ->join('terms', 'terms.id', '=', 'room_terms.term_id')
            ->where('teachers.id', $teacher_id);

        $room_terms_query = DB::table('teachers')
            ->select('terms.id', 'terms.code', 'room_terms.even_odd', 'terms.term_end')
            ->join('room_terms', 'room_terms.teacher_id', 'teachers.id')
            ->join('terms', 'terms.id', '=', 'room_terms.term_id')
            ->where('teachers.id', $teacher_id);

        $terms = $course_teachers_query
            ->unionAll($room_terms_query)
            ->get()
            ->unique(function ($item) {
                return $item->id . $item->even_odd;
            })
            ->sortByDesc('term_end');

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

        $room_term_groups = DB::table('course_teachers')
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
            ->get()
            ->groupBy('grade');

        $managed_room_terms = DB::table('reports')
            ->select(DB::raw('COUNT(reports.id) AS report_count'), 'room_terms.id', 'rooms.name', 'room_terms.even_odd')
            ->rightJoin('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term_id)
            ->where('room_terms.even_odd', $even_odd)
            ->where('room_terms.teacher_id', $teacher_id)
            ->groupBy('room_terms.id', 'rooms.name', 'room_terms.even_odd')
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
            'room_term' => RoomTerm::find($room_term_id)
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

    public function printReport(Report $report)
    {
        $course_report_groups = DB::table('knowledge_grades')
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
            ->where('course_reports.report_id', $report->id)
            ->groupBy(
                'course_reports.course_id',
                'courses.id',
                'courses.name',
                'course_reports.id',
                'courses.group',
                'course_reports.mid_exam',
                'course_reports.mid_exam',
                'course_reports.final_exam',
                'course_reports.knowledge_description',
                'course_reports.skill_description'
            )
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
