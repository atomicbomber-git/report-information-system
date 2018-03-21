<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseReport extends Pivot
{
    protected $table = 'course_reports';

    protected $fillable = [
        'course_id', 'report_id', 'mid_exam', 'final_exam',
        'skill_description', 'knowledge_description', 'spiritual_attitude_description',
        'social_attitude_description'
    ];
}
