<?php

use Illuminate\Database\Seeder;
use App\Course;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_courses = [
            // Kelompok A
            ['grade' => 8, 'name' => 'Pendidikan Agama dan Budi Pekerti', 'group' => 'A'],
            ['grade' => 8, 'name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'group' => 'A'],
            ['grade' => 8, 'name' => 'Bahasa Indonesia', 'group' => 'A'],
            ['grade' => 8, 'name' => 'Matematika', 'group' => 'A'],
            ['grade' => 8, 'name' => 'Ilmu Pengetahuan Alam', 'group' => 'A'],
            ['grade' => 8, 'name' => 'Ilmu Pengetahuan Sosial', 'group' => 'A'],
            ['grade' => 8, 'name' => 'Bahasa Inggris', 'group' => 'A'],
            
            // Kelompok B
            ['grade' => 8, 'name' => 'Seni Budaya', 'group' => 'B'],
            ['grade' => 8, 'name' => 'Pendidikan Jasmani, Olahraga dan Kesehatan', 'group' => 'B'],
            ['grade' => 8, 'name' => 'Prakarya', 'group' => 'B']
        ];
        
        $term = DB::table('terms')->first();

        foreach ($default_courses as $course) {
            $course['term_id'] = $term->id;
            Course::create($course);
        }
    }
}
