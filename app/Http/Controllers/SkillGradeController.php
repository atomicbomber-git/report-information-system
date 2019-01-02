<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\RoomTerm;
use App\Course;
use App\Term;
use App\SkillGrade;
use App\CourseReport;
use App\Helper;

class SkillGradeController extends Controller
{
    // Get every skill type that is currently used in a particular roomterm-course pair
    private function getUsedSkillTypes($room_term_id, $course_id)
    {
        return DB::table('skill_grades')
            ->select('skill_grades.type')

            // Filter by course id
            ->join('course_reports', 'course_reports.id', '=', 'skill_grades.course_report_id')
            ->where('course_reports.course_id', $course_id)

            // Filter by roomterm id
            ->join('reports', 'reports.id',  '=', 'course_reports.report_id')
            ->where('reports.room_term_id', $room_term_id)
            
            ->groupBy('skill_grades.type')
            ->pluck('type');
    }

    // Get skill grades of an entire roomterm-course pair, grouped by the basic competency
    private function getSkillGrades($room_term_id, $course_id)
    {
        return DB::table('reports')
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
            ->orderBy('users.name')
            ->get()
            ->groupBy('basic_competency_name')
            ->map(function ($group) {
                return $group->groupBy('student_name');
            });
    }

    public function skillDetail($term_id, $even_odd, $room_term_id, $course_id)
    {

        $used_skill_types = $this->getUsedSkillTypes($room_term_id, $course_id);
        
        $skill_type_usages = collect(SkillGrade::SCORE_TYPES)
            ->map(
                function($type) use ($used_skill_types) {
                    return [
                        'type' => $type,
                        'is_used' => in_array($type, $used_skill_types->all())
                    ];
                }
            );

        // return $this->getSkillGrades($room_term_id, $course_id);

        return view('teacher_management.skill_detail', [
            'course' => Course::find($course_id),
            'room_term' => RoomTerm::find($room_term_id),
            'skill_grade_groups' => $this->getSkillGrades($room_term_id, $course_id),
            'skill_type_usages' => $skill_type_usages
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

    public function addScoreType()
    {
        $even_odd = RoomTerm::find(request('room_term_id'))->getOriginal('even_odd');

        $course_reports = DB::table('course_reports')
            ->select('course_reports.id')
            ->where('course_reports.course_id', request('course_id'))
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('reports.room_term_id', request('room_term_id'))
            ->get();

        $basic_competencies = DB::table('knowledge_basic_competencies')
            ->select('knowledge_basic_competencies.id')
            ->where('knowledge_basic_competencies.course_id', request('course_id'))
            ->where('knowledge_basic_competencies.even_odd', $even_odd)
            ->get();

        DB::transaction(function() use($course_reports, $basic_competencies) {
            foreach ($course_reports as $course_report) {
                foreach ($basic_competencies as $basic_competency) {
                    SkillGrade::create([
                        'course_report_id' => $course_report->id,
                        'knowledge_basic_competency_id' => $basic_competency->id,
                        'type' => request('type'),
                        'score_1' => NULL,
                        'score_2' => NULL,
                        'score_3' => NULL,
                        'score_4' => NULL,
                        'score_5' => NULL,
                        'score_6' => NULL,
                    ]);
                }
            }
        });
        
        return back()
            ->with('message-success', __('messages.create.success'));
    }

    public function removeScoreType()
    {
        $even_odd = RoomTerm::find(request('room_term_id'))->getOriginal('even_odd');

        DB::table('skill_grades')
            // Filter by skill grade type
            ->where('skill_grades.type', request('type'))

            // Filter by course id
            ->join('course_reports', 'course_reports.id', '=', 'skill_grades.course_report_id')
            ->where('course_reports.course_id', request('course_id'))

            // Filter by roomterm id
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('reports.room_term_id', request('room_term_id'))

            // Filter by even_odd
            ->join('knowledge_basic_competencies', 'knowledge_basic_competencies.id', '=', 'skill_grades.knowledge_basic_competency_id')
            ->where('knowledge_basic_competencies.even_odd', $even_odd)

            ->delete();
        
        return back()
            ->with('message-success', __('messages.delete.success'));
    }

    public function editDescriptions(RoomTerm $room_term, Course $course)
    {
        $descriptions = DB::table('course_reports')
            ->select('course_reports.id AS course_report_id', 'users.name AS student_name', 'students.student_id', 'course_reports.skill_description')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('course_reports.course_id', $course->id)
            ->where('reports.room_term_id', $room_term->id)
            ->get();

        return view('teacher_management.skill_grade.descriptions', [
            'course' => $course,
            'room_term' => $room_term,
            'descriptions' => $descriptions
        ]);
    }

    public function generateDescriptionText($room_term_id, $course_id) {
        $room_term_1 = RoomTerm::find($room_term_id);
        $room_term_2 = RoomTerm::query()
            ->where('id', '<>', $room_term_1->id)
            ->where('room_id', $room_term_1->room_id)
            ->first();

        $skill_grades = DB::table('skill_grades_summary')
            ->select('course_reports.course_id', 'grade', 'reports.student_id')
            ->join('course_reports', 'course_reports.id', '=', 'skill_grades_summary.course_report_id')
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('course_reports.course_id', $course_id)
            ->when($room_term_1->getOriginal('even_odd') == 'odd',
                function ($query) use($room_term_1) {
                    $query->where('skill_grades_summary.room_term_id', $room_term_1->id);
                },
                function ($query) use($room_term_1, $room_term_2) {
                    $query->where(function($query) use($room_term_1, $room_term_2) {
                        $query->where('skill_grades_summary.room_term_id', $room_term_1->id)
                            ->orWhere('skill_grades_summary.room_term_id', $room_term_2->id);
                    });
                }
            )
            ->get();

        $descriptions = $skill_grades
            ->map(function ($record) { 
                return [
                    "student_id" => $record->student_id,
                    "grade" => CourseReport::DESCRIPTIONS[Helper::grade($record->grade)],
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
                    ->update(['skill_description' => $descriptions[$course_report->student_id] ]);
            }
        });

        return back();
    }

    public function processEditDescriptions(RoomTerm $room_term, Course $course)
    {
        $data = request('data');

        DB::transaction(function() use($data) {
            foreach ($data as $course_report_id => $skill_description) {

                DB::table('course_reports')
                    ->where('id', $course_report_id)
                    ->update(['skill_description' => $skill_description]);

            }
        });
    }
}
