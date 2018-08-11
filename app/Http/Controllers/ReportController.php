<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\RoomTerm;
use App\Student;
use App\Report;
use App\Course;
use App\CourseReport;
use App\KnowledgeBasicCompetency;
use App\KnowledgeGrade;
use App\SkillGrade;
use DB;

class ReportController extends Controller
{
    public function create(RoomTerm $room_term)
    {
        $room_term->load('room');

        $students = Student::where('current_grade', $room_term->room->grade)
            ->select('students.id', 'users.name', 'students.student_id')
            
            ->whereNotExists(function ($query) use ($room_term) {
                $query->select('student_id')
                    ->from('reports')
                    ->join('room_terms', 'reports.room_term_id', '=', 'room_terms.id')
                    ->whereRaw('students.id = reports.student_id')
                    ->where('room_terms.even_odd', '=', $room_term->getOriginal('even_odd'))
                    ->where('room_terms.term_id', '=', $room_term->term_id);
            })
            
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('students.active', 1)
            ->orderBy('users.name')
            ->get();

        return view('reports.create',
            [
                'students' => $students,
                'room_term' => $room_term
            ]
        );
    }

    public function processCreate(RoomTerm $room_term)
    {
        // IDs of the students that are going to be added
        $student_ids = request('student_ids');
        
        // All active courses of the room_term's grade
        $courses = Course::select('courses.id')
            ->where('grade', $room_term->room->grade)
            ->where('term_id', $room_term->term_id)
            ->get();

        // All knowledge basic competencies of each respective courses
        $basic_competency_groups = DB::table('knowledge_basic_competencies')
            ->select('knowledge_basic_competencies.id','courses.id AS course_id')
            ->join('courses', 'courses.id', '=', 'knowledge_basic_competencies.course_id')
            ->where('knowledge_basic_competencies.even_odd', $room_term->getOriginal('even_odd'))
            ->where('grade', $room_term->room->grade)
            ->where('courses.term_id', $room_term->term_id)
            ->get()
            ->groupBy('course_id');
        
        $skill_type_groups = DB::table('skill_grades')
            ->select('course_reports.course_id', 'skill_grades.type')
            ->rightJoin('course_reports', 'course_reports.id', '=', 'skill_grades.course_report_id')
            ->join('courses', 'courses.id', '=', 'course_reports.course_id')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('reports.room_term_id', '=', $room_term->id)
            ->whereNotNull('skill_grades.type')
            ->groupBy('course_reports.course_id', 'skill_grades.type')
            ->get()
            ->mapToGroups(function ($item) {
                return [$item->course_id => $item->type];
            });
        
        DB::transaction(function() use ($student_ids, $room_term, $courses, $basic_competency_groups, $skill_type_groups) {
            foreach ($student_ids as $student_id) {
                
                // Report creation
                $report = Report::create([
                    'room_term_id' => $room_term->id,
                    'student_id' => $student_id
                ]);
                
                foreach ($courses as $course) {

                    // Course reports creation
                    $course_report = CourseReport::create([
                        'report_id' => $report->id, 'course_id' => $course->id
                    ]);

                    if ( ! isset($basic_competency_groups[$course->id])) {
                        continue;
                    }
                    
                    foreach ($basic_competency_groups[$course->id] as $basic_competency) {
                        KnowledgeGrade::create([
                            'course_report_id' => $course_report->id,
                            'knowledge_basic_competency_id' => $basic_competency->id
                        ]);
                        
                        if ( ! isset($skill_type_groups[$course->id])) {
                            break;
                        }

                        foreach ($skill_type_groups[$course->id] as $score_type) {
                            SkillGrade::create([
                                'course_report_id' => $course_report->id,
                                'knowledge_basic_competency_id' => $basic_competency->id,
                                'type' => $score_type
                            ]);
                        }
                    }
                }
            }
        });

        request()->session()->flash('message-success', 'Siswa berhasil ditambahkan ke dalam kelas.');

        return ['status' => 'success'];
    }

    public function detail(Report $report)
    {
        $information = Report
            ::select('users.name AS student_name', 'students.student_id', 'terms.code AS term_code', 'room_terms.even_odd AS semester', 'rooms.name AS room_name')
            ->where('reports.id', $report->id)
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')

            ->join('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->join('terms', 'terms.id', '=', 'room_terms.term_id')
            ->first();

        $information->semester = RoomTerm::EVEN_ODD[$information->semester];

        $course_reports = KnowledgeGrade
            ::select(
                'course_reports.id AS id',
                'courses.name',
                'courses.group',
                'course_reports.mid_exam',
                'course_reports.final_exam',
                DB::raw('ROUND(AVG(GREATEST(((first_assignment + second_assignment + third_assignment + first_exam + second_exam ) / 5 ), first_remedial, second_remedial))) AS knowledge_grade')
            )
            ->join('course_reports', 'course_reports.id', '=', 'knowledge_grades.course_report_id')
            ->join('courses', 'courses.id', '=', 'course_reports.course_id')
            ->where('course_reports.report_id', $report->id)
            ->groupBy(
                'course_reports.course_id',
                'courses.name',
                'course_reports.id',
                'courses.group',
                'course_reports.mid_exam',
                'course_reports.final_exam'
            )
            ->get()
            ->groupBy('group');

        return view('reports.detail', [
            'information' => $information,
            'course_reports' => $course_reports,
            'report' => $report
        ]);
    }

    public function delete(Report $report)
    {
        $report->delete();
        return back()->with('message-success', 'Data nilai siswa berhasil dihapus.');
    }

    public function move(Report $report)
    {
        $room_terms = DB::table('room_terms')
            ->select('rooms.name AS room_name', 'room_terms.even_odd', 'room_terms.id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $report->room_term->term_id)
            ->where('room_terms.id', '<>', $report->room_term->id)
            ->where('rooms.grade', $report->room_term->room->grade)
            ->where('room_terms.even_odd', $report->room_term->getOriginal('even_odd'))
            ->orderBy('rooms.grade')
            ->orderBy('rooms.name')
            ->orderBy('room_terms.even_odd')
            ->get();

        return view('reports.move',
            [
                'room_terms' => $room_terms,
                'report' => $report
            ]
        );
    }

    public function processMove(Report $report)
    {
        $report->room_term_id = request('room_term_id');
        $report->save();

        return redirect()->route('reports.move', $report)->with([
            'message-success' => 'Berkas siswa berhasil dipindahkan'
        ]);
    }
}
