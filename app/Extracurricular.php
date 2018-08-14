<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    public $fillable = [
        'name', 'term_id'
    ];
}
