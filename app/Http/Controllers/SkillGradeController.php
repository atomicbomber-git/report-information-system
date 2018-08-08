<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\RoomTerm;
use App\Course;
use App\Term;

class SkillGradeController extends Controller
{
    // Get every skill type that is currently used in a particular roomterm-course pair
    private function usedSkillTypes($room_term_id, $course_id)
    {
        return DB::table('skill_grades')
            ->select('skill_grades.type')
            ->join('course_reports', 'course_reports.id', '=', 'skill_grades.course_report_id')
            ->join('reports', 'reports.id',  '=', 'course_reports.report_id')
            ->where('course_reports.course_id', $course_id)
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
        // return $this->usedSkillTypes($room_term_id, $course_id);

        return view('teacher_management.skill_detail', [
            'course' => Course::find($course_id),
            'room_term' => RoomTerm::find($room_term_id),
            'skill_grade_groups' => $this->getSkillGrades($room_term_id, $course_id)
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
