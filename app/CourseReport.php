<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseReport extends Pivot
{
    public $fillable = ['course_id', 'report_id', 'mid_exam', 'final_exam'];
    
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
