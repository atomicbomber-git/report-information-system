<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\KnowledgeBasicCompetency;
use DB;

class CourseController extends Controller
{
    public function termIndex()
    {
        $terms = DB::table('terms')
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

        Course::create( request()->all() );

        return redirect()
            ->route('courses.grade_index', [$term_id, $grade])
            ->with('message-success', 'Mata pelajaran berhasil ditambahkan');
    }

    public function createKnowledgeBasicCompetency($course_id)
    {
        // TODO: Add validation

        KnowledgeBasicCompetency::create([
            'course_id' => $course_id,
            'name' => request('name'),
            'even_odd' => request('even_odd')
        ]);

        return redirect()->back()
            ->with('message-success', 'Kompetensi dasar berhasil ditambahkan');
    }
}
