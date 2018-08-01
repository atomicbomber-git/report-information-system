<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Teacher;
use DB;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = DB::table('teachers')
            ->select('users.name', 'users.username', 'teachers.teacher_id', 'teachers.id')
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

    public function processCreate()
    {
        // TODO: Add validation!
        $teacher = request()->all();

        DB::transaction(function() use($teacher) {
            // Create user
            $user = User::create([
                'name' => $teacher["name"],
                'username' => $teacher["username,
                'privilege' => 'teacher',
                'password' => bcrypt($teacher->password)
            ]);
            
            // Create teacher
            Teacher::create([
                'user_id' => $user->id,
                'teacher_id' => $teacher->teacher_id,
                'active' => 1
            ]);
        });
    }

    public function delete(Teacher $teacher)
    {
        $teacher->delete();
        return back()->with('message-success', 'Data berhasil dihapus.');
    }
}
