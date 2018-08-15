<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    public $fillable = [
        'name',
        'term_id',
        'teacher_id'
    ];

    public function term()
    {
        return $this->belongsTo(\App\Term::class);
    }

    public function extracurricular_reports()
    {
        return $this->belongsTo(\App\ExtracurricularReport::class);
    }
}
