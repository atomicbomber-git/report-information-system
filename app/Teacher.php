<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public $fillable = ['teacher_id', 'user_id', 'active'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
