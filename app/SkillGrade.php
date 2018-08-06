<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkillGrade extends Model
{
    const SCORE_TYPES = [
        'PRAKTIK',
        'PRODUK',
        'PROYEK',
        'PORTOFOLIO'
    ];

    public $fillable = [
        'course_report_id',
        'knowledge_basic_competency_id',
        'type',
        'score_1',
        'score_2',
        'score_3',
        'score_4',
        'score_5',
        'score_6'
    ];
}
