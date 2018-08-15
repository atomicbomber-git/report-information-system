<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Term;
use App\Extracurricular;
use DB;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $terms = DB::table('terms')
            ->select('terms.id', 'terms.code')
            ->orderByDesc('terms.term_end')
            ->get();

        return view('extracurriculars.index', [
            'terms' => $terms
        ]);
    }

    public function indexTerm(Term $term)
    {
        $extracurriculars = DB::table('extracurriculars')
            ->select('extracurriculars.id', 'extracurriculars.name', 'users.name AS teacher_name', 'teachers.teacher_id')
            ->join('teachers', 'teachers.id', '=', 'extracurriculars.teacher_id')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->where('extracurriculars.term_id', $term->id)
            ->get();

        $teachers = DB::table('teachers')
            ->select('teachers.id', 'teachers.teacher_id', 'users.name')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->where('teachers.active', TRUE)
            ->get();

        return view('extracurriculars.index_term', [
            'term' => $term,
            'extracurriculars' => $extracurriculars,
            'teachers' => $teachers
        ]);
    }

    public function create(Term $term)
    {
        $data = $this->validate(
            request(),
            [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('extracurriculars')
                    ->where(function ($query) use($term) {
                        return $query->where('term_id', $term->id);
                    })
                ],

                'teacher_id' => ['required', 'integer']
            ]
        );

        Extracurricular::create([
            'term_id' => $term->id,
            'name' => $data['name'],
            'teacher_id' => $data['teacher_id']
        ]);

        return back()
            ->with('message-success', __('messages.create.success'));
    }

    public function edit(Extracurricular $extracurricular)
    {
        $teachers = DB::table('teachers')
            ->select('teachers.id', 'teachers.teacher_id', 'users.name')
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->where('teachers.active', TRUE)
            ->get();

        return view('extracurriculars.edit', [
            'extracurricular' => $extracurricular,
            'teachers' => $teachers
        ]);
    }

    public function processEdit(Extracurricular $extracurricular)
    {
        $data = $this->validate(
            request(),
            [
                'name' => Rule::unique('extracurriculars')
                    ->where(function ($query) use($extracurricular) {
                        return $query->where('term_id', $extracurricular->term_id);
                    })
                    ->ignore($extracurricular->id),
                'teacher_id' => ['required', 'integer']
            ]
        );

        $extracurricular->update($data);

        return back()
            ->with('message-success', __('messages.update.success'));
    }

    public function delete(Extracurricular $extracurricular)
    {
        $extracurricular->delete();

        return back()
            ->with('message-success', __('messages.delete.success'));
    }
}
