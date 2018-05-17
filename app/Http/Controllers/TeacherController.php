<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = DB::table('teachers')
            ->select('users.name', 'users.username', 'teachers.teacher_id')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->get();

        return view('teachers.index', [
            'current_page' => 'teachers',
            'teachers' => $teachers
        ]);
    }

    public function create()
    {
        return view('teachers.create');
    }
}
