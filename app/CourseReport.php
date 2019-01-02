<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseReport extends Pivot
{
    protected $table = 'course_reports';

    protected $fillable = [
        'course_id', 'report_id', 'mid_exam', 'final_exam',
        'skill_description', 'knowledge_description',
    ];

    const DESCRIPTIONS = [
        'A' => 'Sangat baik dan sempurna. Dapat mengingat, mengetahui, menerapkan, menganalisis, dan  mengevaluasi semua kompetensi dasar.',
        'B' => 'Baik. Dapat mengingat, mengetahui, menerapkan, menganalis sebagian besar kompetensi dasar tetapi kurang bisa  mengevaluasi dua kompetensi dasar ',
        'C' => 'Cukup. Dapat mengingat, mengetahui sebagian kompetensi dasar,tetapi kurang bisa menerapkan, menganalisis dan mengevaluasi beberapa kompetensi dasar',
        'D' => 'Sangat kurang. Hanya dapat mengingat, mengetahui, menerapkan, menganalisis, dan mengeveluasi satu atau dua kompetensi dasar saja.',
        'E' => 'Gagal.',
    ];
}
