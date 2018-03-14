<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public $fillable = ['name', 'teacher_id'];

    public function teacher() {
        return $this->belongsTo('App\Teacher');
    }

    public function terms()
    {
        return $this->belongsToMany('App\Term', 'room_terms')
            ->withTimeStamps()
            ->as('room_term')
            ->using('App\RoomTerm');
    }
}
