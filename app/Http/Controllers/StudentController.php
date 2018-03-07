<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;

use App\User;
use App\Student;

class StudentController extends Controller
{
    public function index()
    {
        return view('students.index', [
            'students' => Student::all()
        ]);
    }

    public function create()
    {
        return view('students.create');
    }

    public function processCreate()
    {
        // TODO: Add validations

        // Creates user data
        $user = new User;
        $user->name = request('name');
        $user->username = request('username');
        $user->password = bcrypt(request('username')); // TODO: Figure out a better way to generate passowrd
        $user->privilege = 'student'; // TODO: Use enum instead

        // Creates student data
        $student = new Student(request()->all());
        
        // Persist
        DB::transaction(function() use($user, $student) {
            $user->save();
            $user->student()->save($student);
        });

        return redirect()->route('students.index');
    }
}
