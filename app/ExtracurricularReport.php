<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtracurricularReport extends Model
{
    public $fillable = [
        'report_id',
        'extracurricular_id',
        'score'
    ];

    const GRADES = [
        'A', 'B', 'C', 'D', 'E'
    ];

    public function grades()
    {
        return self::GRADES;
    }

    public function extracurricular()
    {
        return $this->belongsTo(\App\Extracurricular::class);
    }

    public function report()
    {
        return $this->belongsTo(\App\Report::class);
    }
}
