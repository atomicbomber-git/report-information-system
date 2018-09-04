<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Term;
use DB;

class HeadmasterAccessController extends Controller
{
    public function terms()
    {
        $terms = DB::table('terms')
            ->select('terms.id AS term_id', 'code', 'even_odd')
            ->join('room_terms', 'room_terms.term_id', '=', 'terms.id')
            ->distinct('term_id', 'even_odd')
            ->orderBy('term_start', 'desc')
            ->get()
            ->groupBy('code');
        
        return view('headmaster_access.terms', compact('terms'));
    }

    public function roomTerms(Term $term, $even_odd)
    {
        $room_terms =  DB::table('room_terms')
            ->select('room_terms.id', 'rooms.name AS room_name')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->get();

        return view('headmaster_access.room_terms', compact('term', 'room_terms'));
    }
}
