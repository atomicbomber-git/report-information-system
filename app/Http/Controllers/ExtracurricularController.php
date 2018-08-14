<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            ['name' => 'required|string']
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

    }

    public function processEdit(Extracurricular $extracurricular)
    {

    }

    public function delete(Extracurricular $extracurricular)
    {
        $extracurricular->delete();

        return back()
            ->with('message-success', __('messages.delete.success'));
    }
}
