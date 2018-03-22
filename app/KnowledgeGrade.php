<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KnowledgeGrade extends Model
{
    public $fillable = [
        'course_report_id',
        'knowledge_basic_competency_id',
        'first_assignment',
        'second_assignment',
        'third_assignment',
        'first_exam',
        'first_remedial',
        'second_exam',
        'second_remedial'
    ];
}
