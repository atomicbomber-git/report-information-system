<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $fillable = ['name', 'grade', 'term_id', 'group', 'type'];

    const TYPES = [
        'normal' => 'Normal',
        'spiritual' => 'Spiritual',
        'social' => 'Social'
    ];

    const GROUPS = [
        'A', 'B'
    ];

    public static function types()
    {
        return self::TYPES;
    }

    public static function groups()
    {
        return self::GROUPS;
    }

    public function term()
    {
        return $this->belongsTo('App\Term');
    }

    public function reports()
    {
        return $this->belongsToMany('App\Report', 'course_reports', 'report_id', 'course_id')
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
