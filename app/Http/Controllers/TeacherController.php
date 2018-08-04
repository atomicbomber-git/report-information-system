<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $teacher = $this->validate(
            request(),
            [
                'name' => 'required|string',
                'username' => 'required|string|alpha_dash',
                'teacher_id' => 'required|string',
                'password' => 'required|string|confirmed'
            ],
            [
                'username.unique' => 'Nama pengguna ini telah dimiliki oleh akun lain.',
                'teacher_id.unique' => 'NIK ini telah dimiliki oleh akun lain.'
            ]
        );

        DB::transaction(function() use($teacher) {
            // Create user
            $user = User::create([
                'name' => $teacher['name'],
                'username' => $teacher['username'],
                'privilege' => 'teacher',
                'password' => bcrypt($teacher['password'])
            ]);
            
            // Create teacher
            Teacher::create([
                'user_id' => $user['id'],
                'teacher_id' => $teacher['teacher_id'],
                'active' => 1
            ]);
        });

        return redirect()
            ->route('teachers.index')
            ->with('message-success', 'Data berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', ['teacher' => $teacher]);
    }

    public function processEdit(Teacher $teacher)
    {
        $new_data = $this->validate(
            request(),
            [
                'name' => 'string|required',
                'username' => ['string', 'required', 'alpha_dash', Rule::unique('users')->ignore($teacher->user->id)],
                'teacher_id' => ['string', 'required', Rule::unique('teachers')->ignore($teacher->id)],
                'password' => 'sometimes|nullable|confirmed|string'
            ],
            [
                'username.unique' => 'Nama pengguna ini telah dimiliki oleh akun lain.',
                'teacher_id.unique' => 'NIK ini telah dimiliki oleh akun lain.'
            ]
        );

        DB::transaction(function() use($teacher, $new_data) {
            // Update user data
            $user = $teacher->user;
            $user->name = $new_data['name'];
            $user->username = $new_data['username']; 

            if ( ! empty($new_data['password'])) {
                $user->password = bcrypt($new_data['password']);
            }

            $user->update();
            
            // Update teacher data
            $teacher->update([
                'teacher_id' => $new_data['teacher_id']
            ]);
        });

        return redirect()
            ->route('teachers.index')
            ->with('message-success', 'Data berhasil diperbarui.');
    }

    public function delete(Teacher $teacher)
    {
        $teacher->delete();
        return back()->with('message-success', 'Data berhasil dihapus.');
    }
}
