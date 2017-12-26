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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/tasks/{task?}', 'TaskController@index')->name('tasks');
    Route::post('/tasks', 'TaskController@store')->name('tasks.store');
    Route::post('/tasks/{task}', 'TaskController@update')->name('tasks.update');
    Route::get('/tasks/{task}/delete', 'TaskController@destroy')->name('tasks.destroy');
    Route::get('/history', 'TaskController@history')->name('task.history');
    Route::get('/restore', 'TaskController@restoreAll')->name('history.restore');
});
