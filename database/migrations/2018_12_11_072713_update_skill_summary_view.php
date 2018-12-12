<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSkillSummaryView extends Migration
{
    protected $view_name = 'skill_grades_summary';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS $this->view_name");

        $subtable = "
            SELECT id, course_report_id, knowledge_basic_competency_id,
                (COALESCE(SUM(score_1), 0) + COALESCE(SUM(score_2), 0) + COALESCE(SUM(score_3), 0) + COALESCE(SUM(score_4), 0) + COALESCE(SUM(score_5), 0) + COALESCE(SUM(score_6), 0)) /
                (COUNT(score_1) + COUNT(score_2) + COUNT(score_3) + COUNT(score_4) + COUNT(score_5) + COUNT(score_6)) AS grade
                FROM skill_grades
                GROUP BY id, course_report_id, knowledge_basic_competency_id, type
        ";

        $subtable = "SELECT id, course_report_id, knowledge_basic_competency_id, AVG(grade) AS grade FROM ($subtable) AS sub GROUP BY knowledge_basic_competency_id, id, course_report_id";

        DB::statement("
            CREATE VIEW $this->view_name AS SELECT room_id, room_term_id, student_id, report_id, course_id, course_report_id, AVG(grade) AS grade FROM ($subtable) AS test
                JOIN course_reports ON course_reports.id = course_report_id
                JOIN reports ON reports.id = report_id
                JOIN room_terms ON room_terms.id = room_term_id
                GROUP BY course_report_id, room_id, room_term_id, student_id, report_id, course_id
                ORDER BY student_id, report_id, course_id, knowledge_basic_competency_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS $this->view_name");
        DB::statement("
            CREATE VIEW $this->view_name AS SELECT room_id, room_term_id, student_id, report_id, course_id, AVG((score_1 + score_2 + score_3 + score_4 + score_5 + score_6) / 6) AS grade FROM skill_grades
                JOIN course_reports ON course_reports.id = course_report_id
                JOIN reports ON reports.id = report_id
                JOIN room_terms ON room_terms.id = room_term_id
                GROUP BY course_report_id, room_id, room_term_id, student_id, report_id, course_id
                ORDER BY student_id, report_id, course_id, knowledge_basic_competency_id
        ");
    }
}
