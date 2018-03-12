<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    public $fillable = ['term_start', 'term_end', 'odd_even', 'code'];
}
