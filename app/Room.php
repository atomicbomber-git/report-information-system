<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public $fillable = ['name'];

    public function teacher() {
        return $this->belongsTo('App\Teacher');
    }

    // odd_even field from the room_terms table.
    public function getEvenOddAttribute()
    {
        return RoomTerm::EVEN_ODD[$this->room_term->even_odd];
    }

    public function terms()
    {
        return $this->belongsToMany('App\Term', 'room_terms')
            ->withTimeStamps()
            ->as('room_term')
            ->withPivot('even_odd', 'teacher_id')
            ->using('App\RoomTerm');
    }
}
