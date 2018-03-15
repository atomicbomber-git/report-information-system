<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RoomTerm;

class Term extends Model
{
    public $fillable = ['term_start', 'term_end', 'code'];

    public function room_terms()
    {
        return $this->hasMany('App\RoomTerm');
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
