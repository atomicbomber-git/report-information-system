<?php

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $room_term_id = 2;
        $course_id = 2;

        $skill_grades = DB::table('reports')
            ->select('users.name AS student_name', 'knowledge_basic_competencies.id AS basic_competency_id', 'knowledge_basic_competencies.name AS basic_competency_name', 'skill_grades.course_report_id', 'skill_grades.type', 'skill_grades.knowledge_basic_competency_id')
            ->join('course_reports', 'course_reports.report_id', '=', 'reports.id')
            ->join('skill_grades', 'skill_grades.course_report_id', '=', 'course_reports.id')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('knowledge_basic_competencies', 'knowledge_basic_competencies.id', '=', 'skill_grades.knowledge_basic_competency_id')
            ->where('reports.room_term_id', $room_term_id)
            ->where('course_reports.course_id', $course_id)
            ->orderBy('basic_competency_name', 'student_name', 'skill_grades.type')
            ->get()
            ->groupBy('basic_competency_name');

        $skill_grades = $skill_grades->map(function ($group) {
            $grouped = $group->groupBy('student_name');
            $grouped = $grouped->sortBy('type');
            return $grouped;
        });

        $skill_types = DB::table('reports')
            ->select('skill_grades.type')
            ->join('course_reports', 'course_reports.report_id', '=', 'reports.id')
            ->join('skill_grades', 'skill_grades.course_report_id', '=', 'course_reports.id')
            ->where('reports.room_term_id', $room_term_id)
            ->where('course_reports.course_id', $course_id)
            ->orderBy('skill_grades.type', 'desc')
            // ->groupBy('skill_grades.type')
            ->get();

        // dd($skill_types);
        dd($skill_grades->keys()->first());

    }
}