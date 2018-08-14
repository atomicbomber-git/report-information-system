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
            ->select('extracurriculars.id', 'extracurriculars.name')
            ->where('extracurriculars.term_id', $term->id)
            ->get();

        return view('extracurriculars.index_term', [
            'term' => $term,
            'extracurriculars' => $extracurriculars
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
                ]
            ]
        );

        Extracurricular::create([
            'term_id' => $term->id,
            'name' => $data['name']
        ]);

        return back()
            ->with('message-success', __('messages.create.success'));
    }

    public function edit(Extracurricular $extracurricular)
    {
        return view('extracurriculars.edit', [
            'extracurricular' => $extracurricular,
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
                    ->ignore($extracurricular->id)
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
