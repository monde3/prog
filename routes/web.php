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

//Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/admin', 'HomeController@index'); 
});

Route::get('logout', function(){
	Auth::logout(); // logout user
	return Redirect::to('/');
});

Route::resource('mistareasactivas', 'MisTareasActivasController');
Route::resource('tareasalumno', 'TareaAlumnoController');

Route::get('mistareasalumno', 'MisTareasActivasController@index');

Route::get('mistareasfinalizadasalumno', 'MisTareasFinalizadasController@index');

Route::get('tareaalumno/{alumno_tarea_id}', [
    'as' => 'tareaalumno',
    'uses' => 'TareaAlumnoController@index'
]);


Route::get('calendarioalumno', function () {
    return view('calendarioalumno');
});
