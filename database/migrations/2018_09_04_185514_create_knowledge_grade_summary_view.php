<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateKnowledgeGradeSummaryView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW knowledge_grades_summary AS SELECT
                term_id,
                room_term_id,
                even_odd,
                student_id,
                report_id,
                course_id,
                knowledge_basic_competency_id,
                GREATEST( ( ( first_assignment + second_assignment + third_assignment + first_exam + second_exam ) / 5 ), first_remedial, second_remedial ) AS knowledge_grade 
            FROM
                knowledge_grades
                INNER JOIN course_reports ON course_reports.id = knowledge_grades.course_report_id
                INNER JOIN reports ON reports.id = course_reports.report_id
                INNER JOIN room_terms ON room_terms.id = reports.room_term_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW knowledge_grades_summary");
    }
}
