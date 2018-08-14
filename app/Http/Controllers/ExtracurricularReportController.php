<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extracurricular;
use App\ExtracurricularReport;
use DB;

class ExtracurricularReportController extends Controller
{
    public function index(Extracurricular $extracurricular, $even_odd)
    {
        $extracurricular_reports = DB::table('extracurricular_reports')
            ->select('extracurricular_reports.id', 'users.name AS student_name', 'students.student_id')
            ->join('reports', 'reports.id', '=', 'extracurricular_reports.report_id')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->where('extracurricular_reports.extracurricular_id', $extracurricular->id)
            ->where('room_terms.even_odd', $even_odd)
            ->get();

        return view('extracurricular_reports.index', [
            'extracurricular_reports' => $extracurricular_reports,
            'extracurricular' => $extracurricular,
            'even_odd' => $even_odd
        ]);
    }

    public function create(Extracurricular $extracurricular, $even_odd)
    {
        $reports = DB::table('reports')
            ->select('reports.id', 'users.name AS student_name', 'students.student_id')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->where('room_terms.even_odd', $even_odd)
            ->whereNotExists(function ($query) use($extracurricular) {
                $query->select('extracurricular_reports.id')
                    ->from('extracurricular_reports')
                    ->whereColumn('extracurricular_reports.report_id', 'reports.id')
                    ->where('extracurricular_reports.extracurricular_id', $extracurricular->id);
            })
            ->get();
        
        return view('extracurricular_reports.create', [
            'extracurricular' => $extracurricular,
            'even_odd' => $even_odd,
            'reports' => $reports
        ]);
    }

    public function processCreate(Extracurricular $extracurricular, $even_odd)
    {
        $report_ids = request('reports');

        DB::transaction(function() use($report_ids, $extracurricular) {
            foreach ($report_ids as $report_id) {
                ExtracurricularReport::create([
                    'report_id' => $report_id,
                    'extracurricular_id' => $extracurricular->id,
                    'score' => 'E'
                ]);
            }
        });        
    }
}
