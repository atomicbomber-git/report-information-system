<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBasicCompetency extends Model
{
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    protected $fillable = [
        'name', 'course_id', 'even_odd'
    ];
}
