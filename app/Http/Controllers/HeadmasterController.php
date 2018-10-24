<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;

class HeadmasterController extends Controller
{
    public function edit()
    {
        $headmaster = User::where('privilege', 'HEADMASTER')->with('teacher:id,user_id,teacher_id')->first();
        return view('headmaster.edit', compact('headmaster'));
    }

    public function update()
    {
        $headmaster = User::where('privilege', 'HEADMASTER')->with('teacher:id,user_id,teacher_id')->first();

        $data = $this->validate(request(), [
            'name' => 'required|string',
            'username' => ['required', 'string', Rule::unique('users')->ignore($headmaster->id)],
            'teacher_id' => ['required', 'string', Rule::unique('teachers')->ignore($headmaster->teacher->id)],
            'password' => 'sometimes|nullable|string|min:8'
        ]);

        // Update teacher's data first
        $headmaster->teacher->update(['teacher_id' => $data['teacher_id']]);
        unset($data['teacher_id']);
        
        // Update user's data
        if (empty($data['password'])) {
            unset($data['password']);
        }
        else {
            $data['password'] = bcrypt($data['password']);
        }

        $headmaster->update($data);

        return redirect()
            ->back()
            ->with('message-success', 'Data berhasil diperbarui.');
    }
}
