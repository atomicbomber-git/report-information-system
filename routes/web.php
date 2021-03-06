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

Route::redirect('/', '/login');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function() {
    Route::prefix('/students')->group(function() {
        Route::get('/', 'StudentController@index')->name('students.index');
        Route::get('/create', 'StudentController@create')->name('students.create');
        Route::post('/create', 'StudentController@processCreate')->name('students.create');
        Route::get('/edit/{student}', 'StudentController@edit')->name('students.edit');
        Route::post('/edit/{student}', 'StudentController@processEdit')->name('students.edit');
        Route::post('/delete/{student}', 'StudentController@delete')->name('students.delete');

        Route::get('/advance_grades/{grade}', 'StudentController@advanceGrades')->name('students.advance_grades');
        Route::post('/advance_grades/{grade}', 'StudentController@processAdvanceGrades')->name('students.advance_grades');
        Route::get('/deactivate/{grade}', 'StudentController@deactivate')->name('students.deactivate');
        Route::post('/deactivate/{grade}', 'StudentController@processDeactivate')->name('students.deactivate');
    });
    
    Route::prefix('/rooms')->group(function() {
        Route::get('/', 'RoomController@index')->name('rooms.index');
        Route::get('/create','RoomController@create')->name('rooms.create');
        Route::post('/create', 'RoomController@processCreate')->name('rooms.create');
        Route::get('/edit/{room}', 'RoomController@edit')->name('rooms.edit');
        Route::post('/edit/{room}', 'RoomController@processEdit')->name('rooms.edit');
        Route::post('/delete/{room}', 'RoomController@delete')->name('rooms.delete');
    });
    
    Route::prefix('/terms')->group(function() {
        Route::get('/', 'TermController@index')->name('terms.index');
        Route::get('/create','TermController@create')->name('terms.create');
        Route::post('/create', 'TermController@processCreate')->name('terms.create');
        Route::get('/edit/{term}', 'TermController@edit')->name('terms.edit');
        Route::post('/edit/{term}', 'TermController@processEdit')->name('terms.edit');
        Route::get('/delete/{term}', 'TermController@delete')->name('terms.delete');
        Route::get('/detail/{term}', 'TermController@detail')->name('terms.detail');
    });

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
        Route::post('/create_from_previous_semester/{room_term}', 'ReportController@createFromPreviousSemester')->name('reports.create_previous');
        Route::post('/delete/{report}', 'ReportController@delete')->name('reports.delete');
        Route::get('/move/{report}', 'ReportController@move')->name('reports.move');
        Route::post('/move/{report}', 'ReportController@processMove')->name('reports.move');
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
        Route::post('/delete/{course}', 'CourseController@delete')->name('courses.delete');
    });

    Route::prefix('/course_teachers')->group(function() {
        Route::get('/terms', 'CourseTeacherController@termIndex')->name('course_teachers.term_index');
        Route::get('/term/{term_id}/semester/{even_odd}/grade/{grade}', 'CourseTeacherController@gradeIndex')->where(['even_odd' => '^(even|odd)$'])->name('course_teachers.grade_index');
        Route::post('/update', 'CourseTeacherController@update')->name('course_teachers.update');
    });

    Route::prefix('/courses')->group(function() {
        Route::get('/terms', 'CourseController@termIndex')->name('courses.term_index');
        Route::get('/term/{term_id}/grade/{grade}', 'CourseController@gradeIndex')->name('courses.grade_index');
        Route::get('/term/{term_id}/grade/{grade}/course/{course_id}', 'CourseController@courseDetail')->name('courses.detail');
        Route::get('/term/{term_id}/grade/{grade}/create', 'CourseController@addCourse')->name('courses.add');
        Route::post('/term/{term_id}/grade/{grade}/create', 'CourseController@createCourse')->name('courses.create');
        Route::post('/course/{course_id}/knowledge_basic_competency/create', 'CourseController@createKnowledgeBasicCompetency')->name('courses.knowledge_basic_competency.create');
        Route::get('/course/{course_id}/knowledge_basic_competency/edit/{basic_competency}', 'CourseController@editKnowledgeBasicCompetency')->name('courses.knowledge_basic_competency.edit');
        Route::post('/course/{course_id}/knowledge_basic_competency/edit/{basic_competency}', 'CourseController@processEditKnowledgeBasicCompetency')->name('courses.knowledge_basic_competency.edit');
        Route::post('/basic_competency/delete/{basic_competency}', 'CourseController@deleteKnowledgeBasicCompetency')->name('courses.knowledge_basic_competency.delete');
    });

    Route::prefix('/teachers')->group(function() {
        Route::get('/', 'TeacherController@index')->name('teachers.index');
        Route::get('/create', 'TeacherController@create')->name('teachers.create');
        Route::post('/create', 'TeacherController@processCreate')->name('teachers.create');
        Route::get('/edit/{teacher}', 'TeacherController@edit')->name('teachers.edit');
        Route::post('/edit/{teacher}', 'TeacherController@processEdit')->name('teachers.edit');
        Route::post('/delete/{teacher}', 'TeacherController@delete')->name('teachers.delete');
    });

    Route::prefix('/extracurriculars')->group(function() {
        Route::get('/', 'ExtracurricularController@index')->name('extracurriculars.index');
        Route::get('/term/{term}/', 'ExtracurricularController@indexTerm')->name('extracurriculars.index_term');
        Route::post('/term/{term}/create', 'ExtracurricularController@create')->name('extracurriculars.create');
        Route::get('/edit/{extracurricular}', 'ExtracurricularController@edit')->name('extracurriculars.edit');
        Route::post('/edit/{extracurricular}', 'ExtracurricularController@processEdit')->name('extracurriculars.edit');
        Route::post('/delete/{extracurricular}', 'ExtracurricularController@delete')->name('extracurriculars.delete');
    
        Route::prefix('/{extracurricular}/reports/{semester}')->group(function() {
            Route::get('/', 'ExtracurricularReportController@index')->name('extracurricular_reports.index');
            Route::get('/create', 'ExtracurricularReportController@create')->name('extracurricular_reports.create');
            Route::post('/create', 'ExtracurricularReportController@processCreate')->name('extracurricular_reports.create');
            Route::post('/delete/{extracurricular_report}', 'ExtracurricularReportController@delete')->name('extracurricular_reports.delete');
        });
    });

    Route::prefix('/teacher_management')->group(function() {
        Route::get('/terms', 'TeacherManagementController@terms')->name('teacher.management.terms');
        Route::get('/room_term/{room_term_id}', 'TeacherManagementController@roomTerm')->name("teacher.management.room_term");
        Route::get('/print_report/{report}/content', 'ReportPrintController@printReport')->name("teacher.management.print_report");
        Route::get('/print_report/{report}/cover', 'ReportPrintController@printReportCover')->name("teacher.management.print_report_cover");
    
        Route::get('/term/{term}/semester/{even_odd}/courses', 'TeacherManagementController@courses')->where(['even_odd' => '^(even|odd)$'])
            ->name('teacher.management.courses');

        Route::get('/grades/{room_term_id}', 'TeacherManagementController@grades')->where(['even_odd' => '^(even|odd)$'])
            ->name('teacher.management.grades');

        Route::get('/term/{term_id}/semester/{even_odd}/room_term/{room_term_id}/course/{course_id}/knowledge', 'TeacherManagementController@courseDetail')
            ->where(['even_odd' => '^(even|odd)$'])
            ->name('teacher.management.courses.detail');
    
        Route::get('/term/{term_id}/semester/{even_odd}/room_term/{room_term_id}/course/{course_id}/exams', 'TeacherManagementController@courseExams')
            ->where(['even_odd' => '^(even|odd)$'])
            ->name('teacher.management.courses.exams');

        Route::get('/room_term/{room_term}/course/{course}/generate_knowledge_description', 'TeacherManagementController@generateDescriptionText')
            ->where(['even_odd' => '^(even|odd)$'])
            ->name('teacher.management.courses.generate_description_text');

        Route::get('/room_term/{room_term}/course/{course}/generate_skill_description', 'SkillGradeController@generateDescriptionText')
            ->where(['even_odd' => '^(even|odd)$'])
            ->name('teacher.management.courses.generate_skill_description_text');
    
        Route::post('/update/knowlegde_grade', 'TeacherManagementController@updateKnowledgeGrade')
            ->name('knowledge_grades.update');
    
        Route::get('/term/{term_id}/semester/{even_odd}/room_term/{room_term_id}/course/{course_id}/skill', 'SkillGradeController@skillDetail')
            ->where(['even_odd' => '^(even|odd)$'])
            ->name('teacher.management.courses.skill_detail');
    
        Route::post('/course_reports/update', 'TeacherManagementController@updateCourseReport')
            ->name('course_reports.update');
    
        Route::prefix('/skill_grade')->group(function() {
            Route::post('/update', 'SkillGradeController@updateSkillGrade')
                ->name('skill_grades.update');
        
            Route::post('/addScoreType', 'SkillGradeController@addScoreType')
                ->name('skill_grades.add_score_type');
    
            Route::post('/removeScoreType', 'SkillGradeController@removeScoreType')
                ->name('skill_grades.remove_score_type');
        });
    
        Route::get('/presence/edit/{room_term}', 'PresenceController@edit')->name('teacher.management.presence.edit');
        Route::post('/presence/edit/{room_term}', 'PresenceController@processEdit')->name('teacher.management.presence.edit');
    
        Route::get('/room_term/{room_term}/course/{course}/descriptions/edit', 'SkillGradeController@editDescriptions')->name('teacher.management.descriptions.edit');
        Route::post('/room_term/{room_term}/course/{course}/descriptions/edit', 'SkillGradeController@processEditDescriptions')->name('teacher.management.descriptions.edit');
    
        Route::get('/room_term/{room_term}/spiritual_description/edit', 'SocialSpiritualDescriptionController@editSpiritualDescriptions')->name('teacher.management.spiritual_description');
        Route::post('/room_term/{room_term}/spiritual_description/edit', 'SocialSpiritualDescriptionController@processEditSpiritualDescriptions')->name('teacher.management.spiritual_description');
        Route::get('/room_term/{room_term}/social_description/edit', 'SocialSpiritualDescriptionController@editSocialDescriptions')->name('teacher.management.social_description');
        Route::post('/room_term/{room_term}/social_description/edit','SocialSpiritualDescriptionController@processEditSocialDescriptions')->name('teacher.management.social_description');
    
        Route::get('/semester/{semester}/extracurricular/{extracurricular}', 'ExtracurricularReportController@editScore')->name('teacher.management.extracurricular_edit_score');
        Route::post('/semester/{semester}/extracurricular/{extracurricular}', 'ExtracurricularReportController@processEditScore')->name('teacher.management.extracurricular_edit_score');
    });

    Route::prefix('/student_access')->group(function() {
        Route::get('/terms', 'StudentAccessController@terms')->name('student_access.terms');
        Route::get('/report/{report}', 'StudentAccessController@report')->name('student_access.report');
    });

    Route::prefix('/headmaster_access')->group(function() {

        Route::get('/terms', 'HeadmasterAccessController@terms')->name('headmaster_access.terms');

        Route::prefix('/grades')->group(function() {
            Route::get('/terms/{term}/room_terms/{even_odd}', 'HeadmasterAccessController@roomTerms')->name('headmaster_access.room_terms');
            Route::get('/room_term/{room_term}', 'HeadmasterAccessController@roomTerm')->name('headmaster_access.room_term');
            Route::get('/terms/{term}/room_terms/{even_odd}/chart', 'HeadmasterAccessController@chart')->name('headmaster_access.chart');
            Route::get('/terms/{term}/room_terms/{even_odd}/best', 'HeadmasterAccessController@best')->name('headmaster_access.best');
        });

        Route::get('/terms/{term}/teachers/{even_odd}', 'HeadmasterAccessController@teachers')->name('headmaster_access.teachers');
        Route::get('/students', 'HeadmasterAccessController@students')->name('headmaster_access.students');
        Route::get('/student/{student}', 'HeadmasterAccessController@student')->name('headmaster_access.student');
    });

    Route::group(['prefix' => '/headmaster', 'as' => 'headmaster.'], function() {
        Route::get('/edit', 'HeadmasterController@edit', 'edit.blade.php')->name('edit');
        Route::post('/update', 'HeadmasterController@update', 'update.blade.php')->name('update');
    });
});