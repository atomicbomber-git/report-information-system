<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use App\RoomTerm;
use App\Report;

class PresenceController extends Controller
{
    public function edit(RoomTerm $room_term)
    {
        $reports = DB::table('reports')
            ->select('users.name', 'reports.id', 'reports.absence_sick', 'reports.absence_permit', 'reports.absence_unknown')
            ->join('students', 'students.id', '=', 'reports.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('reports.room_term_id', $room_term->id)
            ->orderBy('users.name')
            ->get();

        return view('teacher_management.presence.edit', [
            'room_term' => $room_term,
            'reports' => $reports
        ]);
    }

    public function processEdit(RoomTerm $room_term)
    {
        $data = request('data');
        
        DB::transaction(function() use($data) {
            foreach ($data as $report_id => $updates) {
                Report::where('id', $report_id)
                    ->update($updates);
            }
        });

        request()
            ->session()
            ->flash('message-success', __('messages.update.success'));

        return [
            'status' => 'success'
        ];
    }
}
