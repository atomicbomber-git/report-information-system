<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\RoomTerm;
use App\Course;
use App\Term;
use App\SkillGrade;

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
        $course_reports = DB::table('course_reports')
            ->select('course_reports.id')
            ->where('course_reports.course_id', request('course_id'))
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('reports.room_term_id', request('room_term_id'))
            ->get();

        $basic_competencies = DB::table('knowledge_basic_competencies')
            ->select('knowledge_basic_competencies.id')
            ->where('knowledge_basic_competencies.course_id', request('course_id'))
            ->get();

        DB::transaction(function() use($course_reports, $basic_competencies) {
            foreach ($course_reports as $course_report) {
                foreach ($basic_competencies as $basic_competency) {
                    SkillGrade::create([
                        'course_report_id' => $course_report->id,
                        'knowledge_basic_competency_id' => $basic_competency->id,
                        'type' => request('type')
                    ]);
                }
            }
        });
        
        return back()
            ->with('message-success', __('messages.create.success'));
    }

    public function removeScoreType()
    {
        DB::table('skill_grades')
            // Filter by skill grade type
            ->where('skill_grades.type', request('type'))

            // Filter by course id
            ->join('course_reports', 'course_reports.id', '=', 'skill_grades.course_report_id')
            ->where('course_reports.course_id', request('course_id'))

            // Filter by roomterm id
            ->join('reports', 'reports.id', '=', 'course_reports.report_id')
            ->where('reports.room_term_id', request('room_term_id'))

            ->delete();
        
        return back()
            ->with('message-success', __('messages.delete.success'));
    }
}