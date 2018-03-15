<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Term;
use App\RoomTerm;
use DB;

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
        $term->load('room_terms.teacher.user', 'room_terms.room');
        return view('terms.detail', ['term' => $term]);
    }

    public function createRoomTerm(Term $term)
    {
        $vacant_room_terms = $term->crossJoin('rooms')
            ->select('rooms.id', 'temporary.even_odd')
            ->crossJoin(DB::raw("(SELECT 'odd' AS 'even_odd' UNION ALL SELECT 'even') AS temporary"))
            ->leftJoin('room_terms', function ($join) {
                $join->on('terms.id', '=', 'room_terms.term_id');
                $join->on('rooms.id', '=', 'room_terms.room_id');
            })->whereNull('room_terms.id')
            ->get();
        
        return $vacant_room_terms;
        return view('terms.create_room_term', [
            'vacant_room_terms' => $vacant_room_terms
        ]);
    }

    public function deleteRoomTerm(RoomTerm $room_term)
    {
        $room_term->delete();
        return back()->with('message-success', 'Data berhasil dihapus.');
    }
}
