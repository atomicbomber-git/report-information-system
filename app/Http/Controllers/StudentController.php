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
        $students = Student::where('active', 1)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('students.index', [
            'students' => $students,
            'current_page' => 'students'
        ]);
    }

    public function create()
    {
        return view('students.create', ['current_page' => 'students']);
    }

    public function processCreate()
    {
        $data = $this->validate(
            request(),
            [
                'name' => 'required|string',
                'username' => 'required|alpha_dash|min:8',
                'student_id' => 'required|string',
                'password' => 'sometimes|nullable|string|confirmed',
                'birthplace' => 'required|string',
                'birthdate' => 'required|string',
                'religion' => 'required|string'
            ]
        );

        // Creates user data
        $user = new User([
            'name' => $data['name'],
            'username' => $data['username'],
            'privilege' => 'student',
            'password' => filled($data['password']) ? bcrypt($data['password']) : bcrypt($data['username'])
        ]);

        // Creates student data
        $student = new Student([
            'student_id' => $data['student_id'],
            'birthplace' => $data['birthplace'],
            'birthdate' => $data['birthdate'],
            'religion' => $data['religion']
        ]);
        
        // Persist
        DB::transaction(function() use($user, $student) {
            $user->save();
            $user->student()->save($student);
        });

        return redirect()
            ->route('students.index')
            ->with('message-success', 'Data berhasil ditambahkan.');
    }

    public function delete(Student $student)
    {
        $student->delete();
        return back()
            ->with('message-success', 'Data berhasil dihapus.');
    }
}
