<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class StudentAccessController extends Controller
{
    public function terms()
    {
        $terms = DB::table('reports')
            ->select('terms.id', 'terms.code', 'room_terms.even_odd', 'reports.id AS report_id')
            ->join('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->join('terms', 'terms.id', '=', 'room_terms.term_id')
            ->where('reports.student_id', auth()->user()->student->id)
            ->get()
            ->groupBy('code')
            ->map(function($term) {
                $term = $term->keyBy('even_odd');
                return $term;
            });

        return view('student_access.terms', compact('terms'));
    }

    public function reports()
    {

    }
}
