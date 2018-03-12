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

Route::prefix('/semesters')->group(function() {
    Route::get('/', 'SemesterController@index')->name('semesters.index');
    Route::get('/create','SemesterController@create')->name('semesters.create');
    Route::post('/create', 'SemesterController@processCreate')->name('semesters.create');
    Route::get('/edit/{room}', 'SemesterController@edit')->name('semesters.edit');
    Route::post('/edit/{room}', 'SemesterController@processEdit')->name('semesters.edit');
    Route::get('/delete', 'SemesterController@delete')->name('semesters.delete');
});

Route::prefix('/room_semesters')->group(function() {
    Route::get('/', 'RoomSemesterController@index')->name('room_semesters.index');
    Route::get('/create','RoomSemesterController@create')->name('room_semesters.create');
    Route::post('/create', 'RoomSemesterController@processCreate')->name('room_semesters.create');
    Route::get('/edit/{room}', 'RoomSemesterController@edit')->name('room_semesters.edit');
    Route::post('/edit/{room}', 'RoomSemesterController@processEdit')->name('room_semesters.edit');
    Route::get('/delete', 'RoomSemesterController@delete')->name('room_semesters.delete');
});