<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseTeacher extends Model
{
    protected $fillable = [
        'course_id',
        'teacher_id',
        'room_term_id'
    ];
}
