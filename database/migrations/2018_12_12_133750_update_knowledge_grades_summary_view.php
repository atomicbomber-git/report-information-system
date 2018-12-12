<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateKnowledgeGradesSummaryView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS knowledge_grades_summary");
        DB::statement("
            CREATE VIEW knowledge_grades_summary AS SELECT
                knowledge_grades.id,
                room_id,
                room_term_id,
                student_id,
                report_id,
                course_id,
                course_report_id,
                knowledge_basic_competency_id,
                GREATEST( ( ( first_assignment + second_assignment + third_assignment + first_exam + second_exam ) / 5 ), first_remedial, second_remedial ) AS grade 
            FROM
                knowledge_grades
                JOIN course_reports ON course_reports.id = knowledge_grades.course_report_id
                JOIN reports ON reports.id = course_reports.report_id
                JOIN room_terms ON room_terms.id = reports.room_term_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS knowledge_grades_summary");
        DB::statement("
            CREATE VIEW knowledge_grades_summary AS SELECT
                room_id,
                room_term_id,
                student_id,
                report_id,
                course_id,
                knowledge_basic_competency_id,
                GREATEST( ( ( first_assignment + second_assignment + third_assignment + first_exam + second_exam ) / 5 ), first_remedial, second_remedial ) AS grade 
            FROM
                knowledge_grades
                JOIN course_reports ON course_reports.id = knowledge_grades.course_report_id
                JOIN reports ON reports.id = course_reports.report_id
                JOIN room_terms ON room_terms.id = reports.room_term_id
        ");
    }
}
