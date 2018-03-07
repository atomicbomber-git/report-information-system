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
