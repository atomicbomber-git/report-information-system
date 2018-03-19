<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\RoomTerm;
use App\Student;
use App\Report;
use DB;

class ReportController extends Controller
{
    public function create(RoomTerm $room_term)
    {
        $students = Student::where('current_grade', $room_term->grade)
            ->select('students.id', 'users.name', 'students.student_id')
            
            ->whereNotExists(function ($query) use ($room_term) {
                $query->select('student_id')
                    ->from('reports')
                    ->join('room_terms', 'reports.room_term_id', '=', 'room_terms.id')
                    ->whereRaw('students.id = reports.student_id')
                    ->where('room_terms.term_id', '=', $room_term->term_id);
            })
            
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('active', 1)
            ->orderBy('users.name')
            ->get();

        return view('reports.create',
            [
                'students' => $students,
                'room_term' => $room_term
            ]
        );
    }

    public function processCreate(RoomTerm $room_term)
    {
        $student_ids = request('student_ids');

        DB::transaction(function() use ($student_ids, $room_term) {
            foreach ($student_ids as $student_id) {
                Report::create([
                    'room_term_id' => $room_term->id,
                    'student_id' => $student_id
                ]);
            }
        });

        return [
            'status' => 'success'
        ];
    }
}
