<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Semester;

class SemesterController extends Controller
{
    public function index()
    {
        return view('semesters.index', [
            'semesters' => Semester::all()
        ]);
    }
}
