<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminAccountSeeder::class);
        $this->call(HeadmasterAccountSeeder::class);
        $this->call(StudentsTableSeeder::class);
        $this->call(TeachersTableSeeder::class);
        $this->call(RoomsTableSeeder::class);
        $this->call(TermsTableSeeder::class);
        $this->call(RoomTermsTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(CourseReportsTableSeeder::class);
        $this->call(KnowledgeBasicCompetenciesSeeder::class);
        $this->call(ReportsTableSeeder::class);
        $this->call(CourseTeachersTableSeeder::class);
        $this->call(KnowledgeGradesTableSeeder::class);
        $this->call(SkillGradeSeeder::class);
    }
}
