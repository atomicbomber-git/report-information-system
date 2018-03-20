<?php

use Illuminate\Database\Seeder;

use App\Course;
use App\KnowledgeBasicCompetency;

class KnowledgeBasicCompetenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = Course::select('id')->get();

        foreach ($courses as $course) {
            factory(KnowledgeBasicCompetency::class, 5)->make()
                ->each(function($basic_comp) use ($course) {
                    $basic_comp->course_id = $course->id;
                    $basic_comp->save();
                });
        }
    }
}
