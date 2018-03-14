<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Report extends Pivot
{
    protected $table = 'reports';

    protected $fillable = ['student_id', 'room_term_id'];

    public function course_reports()
    {
        return $this->belongsToMany('App\Course', 'course_reports', 'report_id', 'course_id')
            ->withTimeStamps()
            ->as('course_report')
            ->using('App\CourseReport');
    }

    // TEMPORARY WORKAROUND
    public function getUpdatedAtColumn()
    {
        if ($this->pivotParent) {
            return $this->pivotParent->getUpdatedAtColumn();
        }

        return static::UPDATED_AT;
    }

    public function getCreatedAtColumn()
    {
        if ($this->pivotParent) {
            return $this->pivotParent->getCreatedAtColumn();
        }

        return static::CREATED_AT;
    }
}
