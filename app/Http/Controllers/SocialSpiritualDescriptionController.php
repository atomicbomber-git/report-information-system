<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoomTerm;
use App\Report;
use DB;

class SocialSpiritualDescriptionController extends Controller
{
    public function editSpiritualDescriptions(RoomTerm $room_term)
    {
        $reports = DB::table('reports')
            ->select('reports.id', 'users.name AS student_name', 'students.student_id', 'reports.spiritual_attitude_description')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('reports.room_term_id', $room_term->id)
            ->get();

        return view('teacher_management.edit_spiritual_descriptions', [
            'room_term' => $room_term,
            'reports' => $reports
        ]);
    }

    public function processEditSpiritualDescriptions(RoomTerm $room_term)
    {
        $data = request('data');

        DB::transaction(function () use($data) {
            foreach ($data as $report_id => $spiritual_attitude_description) {
                Report::where('id', $report_id)
                    ->update(['spiritual_attitude_description' => $spiritual_attitude_description]);
            }
        });
    }

    public function editSocialDescriptions(RoomTerm $room_term)
    {
        $reports = DB::table('reports')
            ->select('reports.id', 'users.name AS student_name', 'students.student_id', 'reports.social_attitude_description')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('reports.room_term_id', $room_term->id)
            ->get();

        return view('teacher_management.edit_social_descriptions', [
            'room_term' => $room_term,
            'reports' => $reports
        ]);
    }

    public function processEditSocialDescriptions()
    {
        $data = request('data');

        DB::transaction(function () use($data) {
            foreach ($data as $report_id => $social_attitude_description) {
                Report::where('id', $report_id)
                    ->update(['social_attitude_description' => $social_attitude_description]);
            }
        });
    }
}
