<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Term;
use App\RoomTerm;

class TermController extends Controller
{
    public function index()
    {
        return view('terms.index', [
            'terms' => Term::all(),
            'current_page' => 'terms'
        ]);
    }

    public function create()
    {
        return view('terms.create', ['current_page' => 'terms']);
    }

    public function processCreate()
    {
        $this->validate(request(), [
            'term_start' => 'required|integer|min:1800',
            'term_end' => 'required|integer|min:1800',
        ]);

        request()->request->add([
            'code' => request('term_start') . '-' . request('term_end')
        ]);
        
        $this->validate(request(),
            ['code' => 'required|string|unique:terms'],
            ['code.unique' => 'Tahun ajaran ' . request('code') . ' telah ada.']
        );

        Term::create(request()->all());

        return back();
    }

    public function detail(Term $term)
    {
        $term->load('rooms');
        return view('terms.detail', ['term' => $term]);
    }
}
