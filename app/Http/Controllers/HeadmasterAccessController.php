<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Term;
use App\RoomTerm;
use App\Student;
use App\Room;
use App\KnowledgeGradeSummary;
use App\SkillGradeSummary;
use DB;

class HeadmasterAccessController extends Controller
{
    public function terms()
    {
        $male_student_count = collect(DB::select('
            SELECT term_id, COUNT(student_id) AS student_count FROM (
                SELECT DISTINCT room_terms.term_id, reports.student_id FROM room_terms
                    LEFT JOIN reports ON reports.room_term_id = room_terms.id
                    JOIN students ON students.id = reports.student_id
                    WHERE students.sex = \'male\'
                ) AS subtable
                GROUP BY term_id
        '))->mapWithKeys(function ($record) {
            return [$record->term_id => $record->student_count];
        });

        $female_student_count = collect(DB::select('
            SELECT term_id, COUNT(student_id) AS student_count FROM (
                SELECT DISTINCT room_terms.term_id, reports.student_id FROM room_terms
                    LEFT JOIN reports ON reports.room_term_id = room_terms.id
                    JOIN students ON students.id = reports.student_id
                    WHERE students.sex = \'female\'
                ) AS subtable
                GROUP BY term_id
        '))->mapWithKeys(function ($record) {
            return [$record->term_id => $record->student_count];
        });

        $teacher_count = collect(DB::select('
            SELECT term_id, COUNT(teacher_id) AS teacher_count FROM (
                SELECT DISTINCT room_terms.term_id, course_teachers.teacher_id FROM room_terms LEFT JOIN course_teachers
                    ON room_terms.id = course_teachers.room_term_id
                ) AS subtable
                GROUP BY term_id
        '))->mapWithKeys(function ($record) {
            return [$record->term_id => $record->teacher_count];
        });

        $terms = DB::table('terms')
            ->select('code', 'id')
            ->orderByDesc('term_start')
            ->get()
            ->map(function($term) {
                $term->room_term_odd_grades = $this->getRoomTermGradeChartData($term->id, 'odd');
                $term->room_term_even_grades = $this->getRoomTermGradeChartData($term->id, 'even');
                $term->best_even_grades = $this->getBestGrades($term->id, 'even');
                $term->best_odd_grades = $this->getBestGrades($term->id, 'odd');
                return $term;
            })
            ->keyBy('id');

        return view('headmaster_access.terms', compact('terms', 'teacher_count', 'male_student_count', 'female_student_count'));
    }

    public function roomTerms(Term $term, $even_odd)
    {
        $room_terms =  DB::table('room_terms')
            ->select('room_terms.id', 'rooms.name AS room_name')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->get();

        return view('headmaster_access.room_terms', compact('term', 'room_terms', 'even_odd'));
    }

    public function roomTerm(RoomTerm $room_term) {
        $room_term->load([
            'reports:id,room_term_id,student_id',
            'reports.student:id,user_id,student_id',
            'reports.student.user:id,name'
        ]);

        $even_odd = $room_term->getOriginal('even_odd');

        $reports = $room_term->reports->map(function($report) {
            return [
                'student_id' => $report->student->id,
                'student_name' => $report->student->user->name,
                'student_code' => $report->student->student_id
            ];
        })
        ->sortBy('student_name')
        ->keyBy('student_id');

        $knowledge_grades = KnowledgeGradeSummary::query()
            ->select('student_id', DB::raw('AVG(grade) AS grade'))
            ->when($even_odd == 'odd',
                function ($query) use ($room_term) {
                    $query->where('room_term_id', $room_term->id);
                    $query->groupBy('report_id');
                },
                function ($query) use($room_term) {
                    $query->where('room_id', $room_term->room->id);
                    $query->groupBy('student_id');
                }
            )
            ->get()
            ->mapWithKeys(function ($grade) { return [$grade->student_id => $grade["grade"]  ]; });

        $skill_grades = SkillGradeSummary::query()
            ->select('student_id', DB::raw('AVG(grade) AS grade'))
            ->when($even_odd == 'odd',
                function ($query) use ($room_term) {
                    $query->where('room_term_id', $room_term->id);
                    $query->groupBy('report_id', 'student_id');
                },
                function ($query) use($room_term) {
                    $query->where('room_id', $room_term->room->id);
                    $query->groupBy('student_id');
                }
            )
            ->get()
            ->mapWithKeys(function ($grade) { return [$grade->student_id => $grade->grade]; });
        
        $averages = [];

        foreach ($knowledge_grades as $student_id => $knowledge_grade) {
            $averages[] = [
                "id" => $student_id,
                "grade" => ($knowledge_grade + ($skill_grades[$student_id] ?? 0)) / 2
            ];
        }

        $averages = collect($averages);
        $best_grades = $averages->sortByDesc("grade")->values()->take(10);

        // return $best_grades;

        return view('headmaster_access.room_term', compact('room_term', 'reports', 'knowledge_grades', 'skill_grades', 'best_grades'));
    }

    public function teachers(Term $term, $even_odd)
    {
        $teachers = DB::table('teachers')
            ->select(
                'teachers.teacher_id', 'users.name', 'teachers.active',
                DB::raw('GROUP_CONCAT(DISTINCT courses.name ORDER BY courses.name SEPARATOR \', \') AS courses')
            )
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->join('course_teachers', 'course_teachers.teacher_id', '=', 'teachers.id')
            ->join('courses', 'courses.id', '=', 'course_teachers.course_id')
            ->join('room_terms', 'room_terms.id', '=', 'course_teachers.room_term_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('teachers.teacher_id', 'users.name', 'teachers.active')
            ->get();

        $teacher_classes = DB::table('teachers')
            ->select(
                'teachers.teacher_id', 'users.name',
                DB::raw('GROUP_CONCAT(rooms.name ORDER BY rooms.grade, rooms.name SEPARATOR \', \') AS classes')
            )
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->join('room_terms', 'room_terms.teacher_id', '=', 'teachers.id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('teachers.teacher_id', 'users.name')
            ->get()
            ->keyBy('teacher_id');
        
        return view('headmaster_access.teachers', compact('term', 'teachers', 'teacher_classes', 'even_odd'));
    }

    public function students()
    {
        $students = DB::table('students')
            ->select('students.id', 'student_id', 'name', 'sex', 'current_grade')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('active', 1)
            ->orderBy('students.current_grade')
            ->orderBy('users.name')
            ->get();

        return view('headmaster_access.students', compact('students'));
    }

    public function student(Student $student)
    {
        return view('headmaster_access.student', compact('student'));
    }

    public function getRoomTermGradeChartData($term_id, $even_odd) {
        $term = Term::find($term_id);

        $result["knowledge"] = DB::table('knowledge_grades_summary')
            ->select('rooms.name AS room_name', 'room_terms.id AS room_term_id', DB::raw('AVG(knowledge_grades_summary.grade) AS grade_average'))
            ->rightJoin('room_terms', 'room_terms.id', '=', 'knowledge_grades_summary.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('room_terms.id', 'rooms.name')
            ->get();

        $result["skill"] = DB::table('skill_grades_summary')
            ->select('rooms.name AS room_name', 'room_terms.id AS room_term_id', DB::raw('AVG(skill_grades_summary.grade) AS grade_average'))
            ->rightJoin('room_terms', 'room_terms.id', '=', 'skill_grades_summary.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('room_terms.id', 'rooms.name')
            ->get();

        $result["rooms"] = collect($result["knowledge"])->pluck('room_name');
        $result["knowledge"] = collect($result["knowledge"])->pluck('grade_average');
        $result["skill"] = collect($result["skill"])->pluck('grade_average');
        return $result;
    }

    public function chart(Term $term, $even_odd)
    {
        $knowledge_grade_averages = DB::table('knowledge_grades_summary')
            ->select('rooms.name AS room_name', 'room_terms.id AS room_term_id', DB::raw('AVG(knowledge_grades_summary.grade) AS grade_average'))
            ->rightJoin('room_terms', 'room_terms.id', '=', 'knowledge_grades_summary.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('room_terms.id', 'rooms.name')
            ->get();

        $skill_grade_averages = DB::table('skill_grades_summary')
            ->select('rooms.name AS room_name', 'room_terms.id AS room_term_id', DB::raw('AVG(skill_grades_summary.grade) AS grade_average'))
            ->rightJoin('room_terms', 'room_terms.id', '=', 'skill_grades_summary.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->where('room_terms.term_id', $term->id)
            ->where('room_terms.even_odd', $even_odd)
            ->groupBy('room_terms.id', 'rooms.name')
            ->get();

        return view('headmaster_access.chart', compact('knowledge_grade_averages', 'skill_grade_averages', 'term', 'even_odd'));
    }

    public function getBestGrades($term_id, $even_odd)
    {
        $term = Term::find($term_id);

        $reports = DB::table('reports')
            ->select('users.name AS student_name', 'rooms.name AS room_name', 'students.id AS student_id', 'students.student_id AS student_code', 'rooms.name AS room_name')
            ->join('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->join('students', 'reports.student_id', '=', 'students.id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('term_id', $term->id)
            ->groupBy('students.id', 'rooms.name', 'users.name', 'students.student_id', 'rooms.name')
            ->get()
            ->keyBy('student_id');
        
        $knowledge_grades = KnowledgeGradeSummary::query()
            ->select('student_id', DB::raw('AVG(grade) AS grade'))
            ->where('term_id', $term->id)
            ->join('room_terms', 'room_terms.id', 'room_term_id')
            ->when($even_odd == 'odd',
                function ($query) use($even_odd) {
                    $query->where('even_odd', $even_odd);
                    $query->groupBy('report_id');
                },
                function ($query) {
                    $query->groupBy('student_id');
                }
            )
            ->get()
            ->mapWithKeys(function ($grade) { return [$grade->student_id => $grade->grade]; });

        $skill_grades = SkillGradeSummary::query()
            ->select('student_id', DB::raw('AVG(grade) AS grade'))
            ->where('term_id', $term->id)
            ->join('room_terms', 'room_terms.id', 'room_term_id')
            ->when($even_odd == 'odd',
                function ($query) use($even_odd) {
                    $query->where('even_odd', $even_odd);
                    $query->groupBy('report_id', 'student_id');
                },
                function ($query) {
                    $query->groupBy('student_id');
                }
            )
            ->get()
            ->mapWithKeys(function ($grade) { return [$grade->student_id => $grade->grade]; });

        $averages = [];
        foreach ($knowledge_grades as $student_id => $knowledge_grade) {
            $averages[] = [
                "id" => $student_id,
                "grade" => ($knowledge_grade + ($skill_grades[$student_id] ?? 0)) / 2
            ];
        }

        $averages = collect($averages);
        $best_grades = $averages->sortByDesc("grade")->values()->take(10);
    
        return $best_grades->map(function($grade) use($reports, $knowledge_grades, $skill_grades) {
            $grade['data'] = $reports[$grade['id']] ?? [];
            $grade['knowledge_grade'] = $knowledge_grades[$grade['id']];
            $grade['skill_grade'] = $skill_grades[$grade['id']];
            return $grade;
        });
    }

    public function best(Term $term, $even_odd)
    {
        $reports = DB::table('reports')
            ->select('users.name AS student_name', 'rooms.name AS room_name', 'students.id AS student_id', 'students.student_id AS student_code', 'rooms.name AS room_name')
            ->join('room_terms', 'room_terms.id', '=', 'reports.room_term_id')
            ->join('rooms', 'rooms.id', '=', 'room_terms.room_id')
            ->join('students', 'reports.student_id', '=', 'students.id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->where('term_id', $term->id)
            ->groupBy('students.student_id', 'rooms.name', 'users.name', 'students.id', 'rooms.name')
            ->get()
            ->keyBy('student_id');
        
        $knowledge_grades = KnowledgeGradeSummary::query()
            ->select('student_id', DB::raw('AVG(grade) AS grade'))
            ->join('room_terms', 'room_terms.id', 'room_term_id')
            ->when($even_odd == 'odd',
                function ($query) use($even_odd) {
                    $query->where('even_odd', $even_odd);
                    $query->groupBy('report_id');
                },
                function ($query) {
                    $query->groupBy('student_id');
                }
            )
            ->get()
            ->mapWithKeys(function ($grade) { return [$grade->student_id => $grade->grade]; });

        $skill_grades = SkillGradeSummary::query()
            ->select('student_id', DB::raw('AVG(grade) AS grade'))
            ->join('room_terms', 'room_terms.id', 'room_term_id')
            ->when($even_odd == 'odd',
                function ($query) use($even_odd) {
                    $query->where('even_odd', $even_odd);
                    $query->groupBy('report_id', 'student_id');
                },
                function ($query) {
                    $query->groupBy('student_id');
                }
            )
            ->get()
            ->mapWithKeys(function ($grade) { return [$grade->student_id => $grade->grade]; });

        $averages = [];
        foreach ($knowledge_grades as $student_id => $knowledge_grade) {
            $averages[] = [
                "id" => $student_id,
                "grade" => ($knowledge_grade + ($skill_grades[$student_id] ?? 0)) / 2
            ];
        }

        $averages = collect($averages);
        $best_grades = $averages->sortByDesc("grade")->values()->take(10);

        return view('headmaster_access.best', compact("term", "even_odd", "best_grades", "reports", "knowledge_grades", "skill_grades"));
    }
}
