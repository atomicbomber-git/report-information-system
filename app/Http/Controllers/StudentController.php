<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;

use App\User;
use App\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = DB::table('students')
            ->select('students.id', 'students.student_id', 'users.name AS name', 'students.current_grade', 'students.sex', 'students.birthplace', 'students.birthdate', 'users.username')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('students.active', 1)
            ->orderBy('students.current_grade')
            ->orderBy('users.name')
            ->get();

        $advancable_grades = $this->getGrades();
        $last_grade = $advancable_grades->pop();

        return view('students.index', [
            'students' => $students,
            'current_page' => 'students',
            'advancable_grades' => $advancable_grades,
            'last_grade' => $last_grade
        ]);
    }

    public function create()
    {
        return view('students.create', [
            'current_page' => 'students',
            'grades' => $this->getGrades()
        ]);
    }

    public function processCreate()
    {
        $data = collect($this->validate(
            request(),
            [
                'name' => 'required|string',
                'username' => 'required|alpha_dash|min:8|unique:users',
                'student_id' => 'required|string|unique:students',
                'password' => 'sometimes|nullable|string|confirmed',
                'birthplace' => 'required|string',
                'birthdate' => 'required|string',
                'current_grade' => 'required|integer|min:1',
                'religion' => 'required|string',
                'address' => 'required|string',
                'phone' => 'required|string',
                'sex' => 'required|string',
                'nth_child' => 'sometimes|nullable|integer|min:1',
                'mother_name' => 'sometimes|nullable|string',
                'mother_occupation' => 'sometimes|nullable|string',
                'father_name' => 'sometimes|nullable|string',
                'father_occupation' => 'sometimes|nullable|string',
                'parents_address' => 'sometimes|nullable|string',
                'guardian_name' => 'sometimes|nullable|string',
                'guardian_occupation' => 'sometimes|nullable|string',
                'guardian_address' => 'sometimes|nullable|string'
            ]
        ));

        // Creates user data
        $user = new User([
            'name' => $data['name'],
            'username' => $data['username'],
            'privilege' => 'student',
            'password' => filled($data['password']) ? bcrypt($data['password']) : bcrypt($data['username'])
        ]);

        // Creates student data
        $student = new Student(
            $data->except(['name', 'username', 'password'])
                ->all()
        );
        
        // Persist
        DB::transaction(function() use($user, $student) {
            $user->save();
            $user->student()->save($student);
        });

        return redirect()
            ->route('students.index')
            ->with('message-success', __('messages.create.success'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', [
            'student' => $student,
            'grades' => $this->getGrades()
        ]);
    }

    public function processEdit(Student $student)
    {
        $data = collect($this->validate(
            request(),
            [
                'name' => 'required|string',
                'username' => ['required', 'alpha_dash', 'min:8', Rule::unique('users')->ignore($student->user->id)],
                'student_id' => ['required', 'string', Rule::unique('students')->ignore($student->id)],
                'password' => 'sometimes|nullable|string|confirmed',
                'birthplace' => 'required|string',
                'birthdate' => 'required|string',
                'current_grade' => 'required|integer|min:1',
                'religion' => 'required|string',
                'address' => 'required|string',
                'phone' => 'required|string',
                'sex' => 'required|string',
                'nth_child' => 'sometimes|nullable|integer|min:1',
                'mother_name' => 'sometimes|nullable|string',
                'mother_occupation' => 'sometimes|nullable|string',
                'father_name' => 'sometimes|nullable|string',
                'father_occupation' => 'sometimes|nullable|string',
                'parents_address' => 'sometimes|nullable|string',
                'guardian_name' => 'sometimes|nullable|string',
                'guardian_occupation' => 'sometimes|nullable|string',
                'guardian_address' => 'sometimes|nullable|string'
            ]
        ));

        DB::transaction(function() use($student, $data) {
            // Updates user data
            $student->user->update([
                'name' => $data['name'],
                'username' => $data['username'],
                'privilege' => 'student',
                'password' => filled($data['password']) ? bcrypt($data['password']) : bcrypt($data['username'])
            ]);

            // Updates student data
            $student->update(
                $data->except(['name', 'username', 'password'])
                    ->all()
            );
        });

        return back()
            ->with('message-success', __('messages.update.success'));
    }

    public function delete(Student $student)
    {
        $student->delete();
        return back()
            ->with('message-success', 'Data berhasil dihapus.');
    }

    private function getGrades()
    {
        return DB::table('rooms')
            ->select('grade')
            ->groupBy('grade')
            ->get()
            ->pluck('grade');
    }

    public function advanceGrades($grade) {
        $students = DB::table('students')
            ->select('students.id', 'students.student_id', 'users.name AS name', 'students.current_grade', 'students.sex')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('students.active', 1)
            ->where('students.current_grade', $grade)
            ->orderBy('students.current_grade')
            ->orderBy('users.name')
            ->get();

        return view('students.advance_grades', [
            'grade' => $grade,
            'students' => $students
        ]);
    }

    public function processAdvanceGrades($grade)
    {
        $student_ids = request('student_ids');
        
        Student::whereIn('id', $student_ids)
            ->increment('current_grade');

        session()->flash('message-success', __('messages.update.success'));
    }

    public function deactivate($grade)
    {
        $students = DB::table('students')
            ->select('students.id', 'students.student_id', 'users.name AS name', 'students.current_grade', 'students.sex')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('students.active', 1)
            ->where('students.current_grade', $grade)
            ->orderBy('students.current_grade')
            ->orderBy('users.name')
            ->get();

        return view('students.deactivate', [
            'grade' => $grade,
            'students' => $students
        ]);
    }

    public function processDeactivate($grade)
    {
        $student_ids = request('student_ids');
        
        Student::whereIn('id', $student_ids)
            ->update(['active' => 0]);

        session()->flash('message-success', __('messages.update.success'));
    }
}
