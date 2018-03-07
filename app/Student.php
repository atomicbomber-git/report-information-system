<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    const SEXES = [
        "male" => "Laki-Laki",
        "female" => "Perempuan"
    ];

    protected $fillable = [
        'id', 'user_id', 'student_id', 'sex', 'birthplace',
        'birthdate', 'religion', 'status_in_family',
        'nth_child', 'address', 'phone', 'father_name',
        'mother_name', 'parents_address', 'father_occupation',
        'mother_occupation', 'guardian_name', 'guardian_address',
        'guardian_occupation'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
