<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public $fillable = ['teacher_id'];

    public function user()
    {
        $this->belongsTo('App\User');
    }
}
