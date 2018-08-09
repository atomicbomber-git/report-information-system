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

            $basic_comps = collect();
            $basic_comps = $basic_comps->concat(factory(KnowledgeBasicCompetency::class, 2)->make([ 'even_odd' => 'odd' ]));
            $basic_comps = $basic_comps->concat(factory(KnowledgeBasicCompetency::class, 2)->make([ 'even_odd' => 'even' ]));

            $basic_comps->each(function($basic_comp) use ($course) {
                $basic_comp->course_id = $course->id;
                $basic_comp->save();
            });
        }
    }
}
