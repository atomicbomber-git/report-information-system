<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\RoomTerm;
use App\Student;
use App\Report;
use App\Course;
use App\CourseReport;
use DB;

class ReportController extends Controller
{
    public function create(RoomTerm $room_term)
    {
        $students = Student::where('current_grade', $room_term->grade)
            ->select('students.id', 'users.name', 'students.student_id')
            
            ->whereNotExists(function ($query) use ($room_term) {
                $query->select('student_id')
                    ->from('reports')
                    ->join('room_terms', 'reports.room_term_id', '=', 'room_terms.id')
                    ->whereRaw('students.id = reports.student_id')
                    ->where('room_terms.term_id', '=', $room_term->term_id);
            })
            
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('active', 1)
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
        $student_ids = request('student_ids');
        
        $courses = Course::select('courses.id')
            ->where('grade', $room_term->grade)
            ->get();

        DB::transaction(function() use ($student_ids, $room_term, $courses) {
            foreach ($student_ids as $student_id) {
                
                $report = Report::create([
                    'room_term_id' => $room_term->id,
                    'student_id' => $student_id
                ]);

                foreach ($courses as $course) {
                    CourseReport::create(['report_id' => $report->id, 'course_id' => $course->id]);
                }
            }
        });

        request()->session()->flash('message-success', 'Siswa berhasil ditambahkan ke dalam kelas.');

        return ['status' => 'success'];
    }

    public function detail(Report $report)
    {
        $course_reports = CourseReport
            ::select(
                'course_reports.id', 'courses.name', 'courses.group', 'course_reports.mid_exam',
                'course_reports.final_exam', 'courses.has_spiritual_grades'
            )
            ->where('course_reports.report_id', $report->id)
            ->join('courses', 'courses.id', '=', 'course_reports.course_id')
            ->orderBy('courses.group')
            ->get()
            ->groupBy('group');
        
        return view('reports.detail', [
            'course_reports' => $course_reports,
            'report' => $report
        ]);
    }

    public function delete(Report $report)
    {
        $report->delete();
        return back()->with('message-success', 'Data nilai siswa berhasil dihapus.');
    }
}
