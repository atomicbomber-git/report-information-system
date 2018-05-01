<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('/students')->group(function() {
    Route::get('/', 'StudentController@index')->name('students.index');
    Route::get('/create', 'StudentController@create')->name('students.create');
    Route::post('/create', 'StudentController@processCreate')->name('students.create');
});

Route::prefix('/rooms')->group(function() {
    Route::get('/', 'RoomController@index')->name('rooms.index');
    Route::get('/create','RoomController@create')->name('rooms.create');
    Route::post('/create', 'RoomController@processCreate')->name('rooms.create');
    Route::get('/edit/{room}', 'RoomController@edit')->name('rooms.edit');
    Route::post('/edit/{room}', 'RoomController@processEdit')->name('rooms.edit');
    Route::get('/delete', 'RoomController@delete')->name('rooms.delete');
});

Route::prefix('/terms')->group(function() {
    Route::get('/', 'TermController@index')->name('terms.index');
    Route::get('/create','TermController@create')->name('terms.create');
    Route::post('/create', 'TermController@processCreate')->name('terms.create');
    Route::get('/edit/{term}', 'TermController@edit')->name('terms.edit');
    Route::post('/edit/{term}', 'TermController@processEdit')->name('terms.edit');
    Route::get('/delete', 'TermController@delete')->name('terms.delete');
    Route::get('/detail/{term}', 'TermController@detail')->name('terms.detail');

    Route::prefix('/room_terms')->group(function() {
        Route::get('/create/{term}', 'TermController@createRoomTerm')->name('room_terms.create');
        Route::post('/create/{term}', 'TermController@processCreateRoomTerm')->name('room_terms.create');
        Route::get('/detail/{room_term_id}', 'TermController@detailRoomTerm')->name('room_terms.detail');
        Route::post('/update/{room_term}', 'TermController@updateRoomTerm')->name('room_terms.update');
        Route::post('/delete/{room_term}', 'TermController@deleteRoomTerm')->name('room_terms.delete');
    });

    Route::prefix('/reports')->group(function() {
        Route::get('/detail/{report}', 'ReportController@detail')->name('reports.detail');
        Route::get('/create/{room_term}', 'ReportController@create')->name('reports.create');
        Route::post('/create/{room_term}', 'ReportController@processCreate')->name('reports.create');
        Route::post('/delete/{report}', 'ReportController@delete')->name('reports.delete');
    });
});

Route::prefix('/course_reports')->group(function() {
    Route::get('/detail/{course_report_id}', 'CourseReportController@detail')->name('course_reports.detail');
    Route::post('/update/{course_report_id}', 'CourseReportController@update')->name('course_reports.update');
});

Route::prefix('/courses')->group(function() {
    Route::get('/', 'CourseController@index')->name('courses.index');
    Route::get('/create','CourseController@create')->name('courses.create');
    Route::post('/create', 'CourseController@processCreate')->name('courses.create');
    Route::get('/edit/{course}', 'CourseController@edit')->name('courses.edit');
    Route::post('/edit/{course}', 'CourseController@processEdit')->name('courses.edit');
    Route::get('/delete', 'CourseController@delete')->name('courses.delete');
});

Route::prefix('/teacher_management')->group(function() {
    Route::get('/terms', 'TeacherManagementController@terms')->name('teacher.management.terms');

    Route::get('/terms/{term_id}/{even_odd}/courses', 'TeacherManagementController@courses')
        ->where(['even_odd' => '^(even|odd)$'])
        ->name('teacher.management.courses');

    Route::get('/terms/{term_id}/{even_odd}/room_terms/{room_term_id}/courses/{course_id}', 'TeacherManagementController@courseDetail')
        ->where(['even_odd' => '^(even|odd)$'])
        ->name('teacher.management.courses.detail');

    Route::get('/terms/{term_id}/{even_odd}/room_terms/{room_term_id}/courses/{course_id}/exams', 'TeacherManagementController@courseExams')
        ->where(['even_odd' => '^(even|odd)$'])
        ->name('teacher.management.courses.exams');

    Route::post('/update/knowlegde_grade', 'TeacherManagementController@updateKnowledgeGrade')
        ->name('knowledge_grades.update');

    Route::post('/update/course_report', 'TeacherManagementController@updateCourseReport')
        ->name('course_reports.update');
});

Route::prefix('/course_teachers')->group(function() {

    Route::get('/terms', 'CourseTeacherController@termIndex')->name('course_teachers.term_index');
    Route::get('/term/{term_id}/even_odd/{even_odd}/grade/{grade}', 'CourseTeacherController@gradeIndex')
        ->where(['even_odd' => '^(even|odd)$'])
        ->name('course_teachers.grade_index');

    Route::post('/update', 'CourseTeacherController@update')->name('course_teachers.update');
});

Route::prefix('/courses')->group(function() {

    Route::get('/terms', 'CourseController@termIndex')->name('courses.term_index');
    
    Route::get('/term/{term_id}/grade/{grade}', 'CourseController@gradeIndex')
        ->name('courses.grade_index');

    Route::get('/term/{term_id}/grade/{grade}/course/{course_id}', 'CourseController@courseDetail')
        ->name('courses.detail');
});

Route::prefix('/teachers')->group(function() {
    Route::get('/', 'TeacherController@index')->name('teachers.index');
});