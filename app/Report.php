<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'student_id',
        'room_term_id',
        'absence_sick',
        'absence_permit',
        'absence_unknown',
        'social_attitude_description',
        'spiritual_attitude_description'
    ];

    public function student()
    {
        return $this->belongsTo('App\Student');
    }

    public function room_term()
    {
        return $this->belongsTo('App\RoomTerm');
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
