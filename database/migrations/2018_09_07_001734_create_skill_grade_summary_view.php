<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSkillGradeSummaryView extends Migration
{
    protected $view_name = 'skill_grades_summary';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW $this->view_name AS SELECT room_id, room_term_id, student_id, report_id, course_id, AVG((score_1 + score_2 + score_3 + score_4 + score_5 + score_6) / 6) AS grade FROM skill_grades
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
        DB::statement("DROP VIEW $this->view_name");
    }
}
