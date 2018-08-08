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

    protected $fillable = ['room_id', 'term_id', 'even_odd', 'teacher_id', 'grade'];

    // odd_even field from the room_terms table.
    public function getEvenOddAttribute($value)
    {
        return RoomTerm::EVEN_ODD[$value];
    }

    public function teacher()
    {
        return $this->belongsTo('App\Teacher');
    }

    public function room() {
        return $this->belongsTo('App\Room');
    }

    public function term() {
        return $this->belongsTo('App\Term');
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
