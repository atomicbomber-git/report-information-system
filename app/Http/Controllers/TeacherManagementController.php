<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Helper;
use App\RoomTerm;
use App\Term;
use App\Course;
use App\Report;
use App\KnowledgeGrade;
use App\KnowledgeGradeSummary;
use App\SkillGrade;
use App\SkillGradeSummary;
use App\CourseReport;

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

    public function courses(Term $term, $even_odd)
    {
        $teacher_id = auth()->user()->teacher->id;

        $room_term_groups = DB::table('course_teachers')
            ->select(
                'rooms.name AS room_name',
                'courses.name AS course_name',
                'courses.type AS course_type',
                'courses.id AS course_id', 'rooms.grade',
                DB::raw('COUNT(reports.id) AS report_count'),
                'room_terms.id'
            )
            ->join('room_terms', 'room_terms.id', '=', 'course_teachers.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->join('courses', 'courses.id', '=', 'course_teachers.course_id')
            ->leftJoin('reports', 'reports.room_term_id', '=', 'room_terms.id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->where('course_teachers.teacher_id', $teacher_id)
            ->groupBy('room_terms.id', 'rooms.name', 'courses.name', 'courses.type', 'courses.id', 'rooms.grade')
            ->get()
            ->groupBy('grade');

        $managed_room_terms = DB::table('reports')
            ->select(DB::raw('COUNT(reports.id) AS report_count'), 'room_terms.id', 'rooms.name', 'room_terms.even_odd')
            ->rightJoin('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->where('room_terms.teacher_id', $teacher_id)
            ->groupBy('room_terms.id', 'rooms.name', 'room_terms.even_odd')
            ->get();

        $managed_extracurriculars = DB::table('extracurriculars')
            ->select('extracurriculars.id', DB::raw('COUNT(extracurricular_reports.id) AS member_count'), 'extracurriculars.name')
            ->leftJoin('extracurricular_reports', 'extracurricular_reports.extracurricular_id', '=', 'extracurriculars.id')
            ->leftJoin('reports', 'reports.id', '=', 'extracurricular_reports.report_id')
            ->leftJoin('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->where('extracurriculars.teacher_id', $teacher_id)
            ->where('extracurriculars.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('extracurriculars.id', 'extracurriculars.name')
            ->orderBy('extracurriculars.name')
            ->get();
        
        return view('teacher_management.courses', [
            'room_term_groups' => $room_term_groups,
            'managed_extracurriculars' => $managed_extracurriculars,
            'managed_room_terms' => $managed_room_terms,
            'term' => $term,
            'even_odd' => $even_odd
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
            ->select('course_reports.id', 'users.name', 'mid_exam', 'final_exam', 'knowledge_description')
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

    public function generateDescriptionText($room_term_id, $course_id)
    {
        $room_term_1 = RoomTerm::find($room_term_id);
        $room_term_2 = RoomTerm::query()
            ->where('id', '<>', $room_term_1->id)
            ->where('room_id', $room_term_1->room_id)
            ->first();

        $knowledge_grades = DB::table('knowledge_grades_summary')
            ->select('knowledge_grades_summary.id', 'knowledge_grades_summary.course_id', DB::raw('((AVG(grade) + final_exam + mid_exam) / 3)  AS knowledge_grade'), 'course_report_id', 'reports.student_id')
            ->join('course_reports', 'course_reports.id', '=', 'knowledge_grades_summary.course_report_id')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('course_reports.course_id', $course_id)
            ->when($room_term_1->getOriginal('even_odd') == 'odd',
                function ($query) use($room_term_1) {
                    $query->where('knowledge_grades_summary.room_term_id', $room_term_1->id);
                },
                function ($query) use($room_term_1, $room_term_2) {
                    $query->where(function($query) use($room_term_1, $room_term_2) {
                        $query->where('knowledge_grades_summary.room_term_id', $room_term_1->id)
                            ->orWhere('knowledge_grades_summary.room_term_id', $room_term_2->id);
                    });
                }
            )
            ->groupBy('course_report_id', 'mid_exam', 'final_exam', 'knowledge_grades_summary.course_id', 'student_id')
            ->get();

        $descriptions = $knowledge_grades
            ->map(function ($record) { 
                return [
                    "student_id" => $record->student_id,
                    "grade" => CourseReport::DESCRIPTIONS[Helper::grade(number_format($record->knowledge_grade, 0))],
                ];
            })
            ->mapWithKeys(function ($record) { return [$record["student_id"] => $record["grade"]]; });

        $course_reports = DB::table('course_reports')
            ->select('course_reports.id', 'reports.student_id')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('course_reports.course_id', $course_id)
            ->where('reports.room_term_id', $room_term_1->id)
            ->get();

        DB::transaction(function() use($descriptions, $course_reports) {
            foreach ($course_reports as $course_report) {
                DB::table('course_reports')
                    ->where('id', $course_report->id)
                    ->update(['knowledge_description' => $descriptions[$course_report->student_id] ]);
            }
        });

        return back();
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

    public function grades($room_term_id)
    {
        $room_term = RoomTerm::find($room_term_id);
        
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

        return view('teacher_management.grades', compact('room_term', 'reports', 'knowledge_grades', 'skill_grades'));
    }
}
