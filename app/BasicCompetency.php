<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BasicCompetency extends Model
{
    public $fillable = [
        'name',
        'description',
        'course_report_id',
        'first_assignment',
        'second_assignment',
        'third_assignment',
        'first_exam',
        'first_remedial',
        'second_exam',
        'second_remedial'
    ];

    public function course_report()
    {
        return $this->belongsTo('App\CourseReport', 'id', 'course_report_id');
    }
}
