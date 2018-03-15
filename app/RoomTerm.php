<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoomTerm extends Pivot
{
    protected $table = 'room_terms';

    const EVEN_ODD = [
        'odd' => 'Ganjil',
        'even' => 'Genap'
    ];

    public $fillable = ['room_id', 'term_id', 'even_odd', 'teacher_id'];

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    public function students()
    {
        return $this->belongsToMany('App\Student', 'reports', 'room_term_id', 'student_id')
            ->withTimeStamps()
            ->as('report')
            ->using('App\Report');
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
