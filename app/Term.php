<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    public $fillable = ['term_start', 'term_end', 'code'];

    public function rooms()
    {
        return $this->belongsToMany('App\Room', 'room_terms')
            ->withTimestamps()
            ->as('room_term')
            ->using('App\RoomTerm');
    }
}
