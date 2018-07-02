<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\CourseTeacher;
use App\KnowledgeBasicCompetency;
use App\KnowledgeGrade;
use App\CourseReport;
use DB;

class CourseController extends Controller
{
    public function termIndex()
    {
        $terms = DB::table('terms')
            ->orderBy('term_start', 'desc')
            ->get();

        $grades = DB::table('rooms')
            ->select('rooms.grade')
            ->groupBy('rooms.grade')
            ->get()
            ->map(function ($room) { return $room->grade; });

        return view('courses.terms', [
            'current_page' => 'courses',
            'terms' => $terms,
            'grades' => $grades
        ]);
    }

    public function gradeIndex($term_id, $grade)
    {
        $courses = DB::table('courses')
            ->where('grade', $grade)
            ->where('term_id', $term_id)
            ->get();

        $information = new \stdClass();
        $information->term = DB::table('terms')
            ->where('terms.id', $term_id)
            ->first();
        $information->grade = $grade;

        return view('courses.grade_index', [
            'information' => $information,
            'courses' => $courses,
        ]);
    }

    public function courseDetail($term_id, $grade, $course_id)
    {   
        $basic_competencies = DB::table('knowledge_basic_competencies')
            ->where('course_id', $course_id)
            ->orderBy('even_odd', 'desc')
            ->get();

        $information = new \stdClass();
        $information->term = DB::table('terms')
            ->where('terms.id', $term_id)
            ->first();
        $information->course = DB::table('courses')
            ->where('courses.id', $course_id)
            ->first();
        $information->grade = $grade;

        return view('courses.course_detail', [
            'basic_competencies' => $basic_competencies,
            'information' => $information,
        ]);
    }

    public function addCourse($term_id, $grade)
    {
        $information = new \StdClass();
        $information->term = DB::table('terms')->where('id', $term_id)->first();
        $information->grade = $grade;

        return view('courses.create', [
            'information' => $information
        ]);
    }

    public function createCourse($term_id, $grade)
    {
        // TODO: Add validation later

        DB::transaction(function() use($term_id, $grade) {

            $course = Course::create( request()->all() );

            $room_terms = DB::table('room_terms')
                ->select('room_terms.id')
                ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
                ->where('room_terms.term_id', $term_id)
                ->where('rooms.grade', $grade)
                ->get();

            // Create course-teacher-room_term mappings for the new course
            foreach ($room_terms as $room_term) {
                CourseTeacher::create([
                    'room_term_id' => $room_term->id,
                    'course_id' => $course->id
                ]);
            }

            // Update all reports in the term
            $reports = DB::table('reports')
                ->select('reports.id AS id')
                ->join('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
                ->where('room_terms.term_id', $term_id)
                ->get();

            foreach ($reports as $report) {
                CourseReport::create(['report_id' => $report->id, 'course_id' => $course->id]);
            }
        });

        return redirect()
            ->route('courses.grade_index', [$term_id, $grade])
            ->with('message-success', 'Mata pelajaran berhasil ditambahkan');
    }

    public function createKnowledgeBasicCompetency($course_id)
    {
        // TODO: Add validation

        DB::transaction(function() use($course_id) {
            $basic_competency = KnowledgeBasicCompetency::create([
                'course_id' => $course_id,
                'name' => request('name'),
                'even_odd' => request('even_odd')
            ]);

            $course_reports = DB::table('course_reports')
                ->where('course_reports.course_id', $course_id)
                ->get();

            foreach ($course_reports as $course_report) {
                KnowledgeGrade::create([
                    'course_report_id' => $course_report->id,
                    'knowledge_basic_competency_id' => $basic_competency->id
                ]);
            }
        });

        return redirect()->back()
            ->with('message-success', 'Kompetensi dasar berhasil ditambahkan');
    }

    public function delete(Course $course)
    {
        $course->delete();
        return redirect()->back();
    }
}
