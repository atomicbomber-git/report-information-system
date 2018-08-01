<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public $fillable = ['name', 'grade'];

    public function terms()
    {
        return $this->belongsToMany('App\Term', 'room_terms')
            ->withTimeStamps()
            ->as('room_term')
            ->withPivot('even_odd', 'teacher_id')
            ->using('App\RoomTerm');
    }
}
