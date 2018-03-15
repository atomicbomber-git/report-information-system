<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RoomTerm;

class Term extends Model
{
    public $fillable = ['term_start', 'term_end', 'code'];

    // odd_even field from the room_terms table.
    public function getEvenOddAttribute()
    {
        RoomTerm::$EVEN_ODD[$this->room_term->even_odd];
    }

    public function rooms()
    {
        return $this->belongsToMany('App\Room', 'room_terms')
            ->withTimestamps()
            ->as('room_term')
            ->withPivot('even_odd', 'teacher_id')
            ->using('App\RoomTerm');
    }
}
