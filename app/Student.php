<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    const SEXES = [
        "male" => "Laki-Laki",
        "female" => "Perempuan"
    ];

    const RELIGIONS = [
        'islam' => 'Islam',
        'catholicism' => 'Kristen Katolik',
        'protestantism' => 'Kristen Protestan',
        'buddhism' => 'Buddha',
        'hinduism' => 'Hindu'
    ];

    protected $fillable = [
        'id', 'user_id', 'student_id', 'sex', 'birthplace',
        'birthdate', 'religion', 'status_in_family',
        'nth_child', 'address', 'phone', 'father_name',
        'mother_name', 'parents_address', 'father_occupation',
        'mother_occupation', 'guardian_name', 'guardian_address',
        'guardian_occupation', 'current_grade'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function room_terms()
    {
        return $this->belongsToMany('App\RoomTerm', 'reports', 'room_term_id', 'student_id')
            ->withTimeStamps()
            ->as('report')
            ->using('App\Report');
    }
}
