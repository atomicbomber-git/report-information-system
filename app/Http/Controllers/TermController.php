<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Term;
use App\RoomTerm;
use App\Teacher;
use App\Report;
use Illuminate\Validation\Rule;
use DB;

class TermController extends Controller
{
    public function index()
    {
        return view('terms.index', [
            'terms' => Term::orderBy('term_start', 'desc')->get(),
            'current_page' => 'terms'
        ]);
    }

    public function create()
    {
        return view('terms.create',
            [
                'current_page' => 'terms',
                'latest_year' => Term::orderBy('term_end', 'desc')->value('term_end') ?? today()->year
            ]
        );
    }

    public function processCreate()
    {
        request()->request->add([
            'code' => request('term_start') . '-' . request('term_end')
        ]);

        $this->validate(
            request(), 
            [
                'term_start' => 'required|integer|min:1800',
                'term_end' => 'required|integer|min:1800',
                'passing_grade' => 'required|integer|min:0|max:100',
                'code' => [
                    'required',
                    'string',
                    'unique:terms',
                    function($attribute, $value, $fail) {
                        if (request('term_end') - request('term_start') !== 1) {
                            $fail('Tahun akhir wajib berselisih satu tahun dengan tahun mulai');
                        }
                    }
                ]
            ],
            [
                'code.unique' => 'Tahun ajaran ' . request('code') . ' telah ada.'
            ]
        );

        Term::create(request()->all());

        return redirect()
            ->route('terms.index')
            ->with('message-success', __('messages.create.success'));
    }

    public function edit(Term $term)
    {
        return view('terms.edit', ['term' => $term]);
    }

    public function processEdit(Term $term)
    {
        request()->request->add([
            'code' => request('term_start') . '-' . request('term_end')
        ]);

        $this->validate(
            request(), 
            [
                'term_start' => 'required|integer|min:1800',
                'term_end' => 'required|integer|min:1800',
                'passing_grade' => 'required|integer|min:0|max:100',
                'code' => [
                    'required',
                    'string',
                    Rule::unique('terms')->ignore($term->id),
                    function($attribute, $value, $fail) {
                        if (request('term_end') - request('term_start') !== 1) {
                            $fail('Tahun akhir wajib berselisih satu tahun dengan tahun mulai');
                        }
                    }
                ]
            ],
            [
                'code.unique' => 'Tahun ajaran ' . request('code') . ' telah ada.'
            ]
        );

        $term->update(request()->all());
        
        return redirect()
            ->route('terms.index')
            ->with('message-success', __('messages.update.success'));
    }

    public function delete(Term $term)
    {
        $term->delete();
        return back()
            ->with('message-success', __('messages.delete.success'));
    }

    public function detail($term_id)
    {
        $term = Term::find($term_id);

        $room_terms = DB::table('reports')
            ->select(
                'room_terms.id',
                'rooms.name AS room_name',
                'rooms.grade AS grade',
                'room_terms.even_odd',
                'users.name AS teacher_name',
                'teachers.teacher_id',
                DB::raw('COUNT(reports.id) AS report_count')
            )
            ->rightJoin('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->leftJoin('teachers', 'teachers.id', '=', 'room_terms.teacher_id')
            ->leftJoin('users', 'users.id', '=', 'teachers.user_id')
            ->groupBy('room_terms.id', 'rooms.name', 'rooms.grade', 'room_terms.even_odd', 'users.name', 'teachers.teacher_id')
            ->where('room_terms.term_id', $term_id)
            ->orderBy('rooms.grade')
            ->orderBy('rooms.name')
            ->get();
        
        return view('terms.detail',
            [
                'term' => $term,
                'room_terms' => $room_terms,
                'vacant_room_count' => $this->getVacantRooms($term_id)->count()
            ]
        );
    }

    public function detailRoomTerm($room_term_id)
    {
        $room_term = RoomTerm::where('room_terms.id', $room_term_id)
            ->select('room_terms.id', 'room_terms.even_odd', 'rooms.name', 'rooms.id AS room_id', 'terms.code', 'room_terms.term_id', 'room_terms.teacher_id')
            ->join('terms', 'room_terms.term_id', '=', 'terms.id')
            ->join('rooms', 'room_terms.room_id', '=', 'rooms.id')
            ->first();

        $reports = Report::select('reports.id', 'users.name', 'students.student_id')
            ->where('room_term_id', $room_term_id)
            ->join('students', 'reports.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name')
            ->get();
        
        $teachers = Teacher::select('teachers.id', 'users.name', 'teachers.teacher_id')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->where('teachers.active', 1)
            ->get();

        $odd_report_count = DB::table('reports')
            ->select(DB::raw('COUNT(reports.id) as count'))
            ->join('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->where('room_terms.room_id', $room_term->room_id)
            ->where('room_terms.even_odd', 'odd')
            ->first()
            ->count;

        return view('room_terms.detail',
            [
                'room_term' => $room_term,
                'odd_report_count' => $odd_report_count,
                'reports' => $reports,
                'teachers' => $teachers
            ]
        );
    }

    public function updateRoomTerm(RoomTerm $room_term)
    {
        $room_term->update([
            'teacher_id' => request('teacher_id')
        ]);

        return back()
            ->with('message-success', __('messages.update.success'));
    }

    // Get all the room-term pairs that haven't been added to the room_terms table yet
    private function getVacantRooms($term_id) {
        $semestersSQL = "SELECT 'even' AS 'even_odd' UNION SELECT 'odd' AS 'even_odd'";
        
        $possibleClassesSQL = DB::table('rooms')
            ->select('rooms.id', 'rooms.name', 'semesters.even_odd')
            ->crossJoin(DB::raw("($semestersSQL) AS semesters"))
            ->toSql();

        return DB::table(DB::raw("($possibleClassesSQL) AS possible_classes"))
            ->whereNotExists(function ($query) use ($term_id) {
                $query
                    ->select('room_terms.room_id', 'room_terms.even_odd')
                    ->from('room_terms')
                    ->whereRaw('room_terms.room_id = possible_classes.id')
                    ->whereRaw('room_terms.even_odd = possible_classes.even_odd')
                    ->where('room_terms.term_id', $term_id);
            })
            ->get();
    }

    public function createRoomTerm(Term $term)
    {
        $vacant_rooms = $this->getVacantRooms($term->id);
        $teachers = Teacher::where('active', 1)->with('user')->get();

        return view('terms.create_room_term', [
            'term_id' => $term->id,
            'vacant_rooms' => $vacant_rooms,
            'teachers' => $teachers
        ]);
    }

    public function processCreateRoomTerm(Term $term)
    {
        $this->validate(request(), [
            'room_id' => 'required|integer',
            'teacher_id' => 'sometimes|integer'
        ]);

        request()->request->add(['term_id' => $term->id]);
        
        $grade = DB::table('rooms')
            ->where('id', request('room_id'))
            ->first()->grade;

        $courses = DB::table('courses')
            ->where('grade', $grade)
            ->where('term_id', $term->id)
            ->get();
        
        DB::transaction(function() use($courses) {
            $room_term = RoomTerm::create( request()->all());
        
            foreach ($courses as $course) {
                DB::table('course_teachers')->insert([
                    'course_id' => $course->id,
                    'room_term_id' => $room_term->id
                ]);
            }
        });
        
        return redirect()
            ->route('terms.detail', $term)
            ->with('message-success', __('messages.create.success'));
    }

    public function deleteRoomTerm(RoomTerm $room_term)
    {
        $room_term->delete();
        return back()->with('message-success', __('messages.delete.success'));
    }
}
