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
Route::resource('tiempotarea', 'TiempoTareaController');
Route::resource('micalendario', 'CalendarioController');
Route::resource('misusuarios', 'UserController');

Route::get('mistareas',  [
    'as' => 'mistareas',
    'middleware' => 'auth',
    'uses' => 'MisTareasActivasController@index'
]);

Route::get('mistareasfinalizadas', [
    'as' => 'mistareasfinalizadas',
    'middleware' => 'auth',
    'uses' => 'MisTareasFinalizadasController@index'
]);

Route::get('usuarios', [
    'as' => 'usuarios',
    'middleware' => 'auth',
    'uses' => 'UserController@index'
]);

Route::get('tareaalumno/{alumno_tarea_id}', [
    'as' => 'tareaalumno',
    'middleware' => 'auth',
    'uses' => 'TareaAlumnoController@index'
]);

Route::get('tareaprofesor/{cod_tarea}', [
    'as' => 'tareaprofesor',
    'middleware' => 'auth',
    'uses' => 'TareaProfesorController@index'
]);


Route::get('graficaalumno/{alumno_tarea_id}', [
    'as' => 'graficaalumno',
    'middleware' => 'auth',
    'uses' => 'GraficaAlumnoController@index'
]);

Route::get('graficaprofesor/{cod_tarea}', [
    'as' => 'graficaprofesor',
    'middleware' => 'auth',
    'uses' => 'GraficaProfesorController@index'
]);

Route::get('calendario', function () {
    return view('calendario');
});

Route::get('cronometrotarea/{alumno_tarea_id}', [
    'as' => 'cronometrotarea',
    'middleware' => 'auth',
    'uses' => 'MisTareasActivasController@tiempo'
]);
Route::get('cronometrotiempo/{tiempo_tarea_id}', [
    'as' => 'cronometrotiempo',
    'middleware' => 'auth',
    'uses' => 'TiempoTareaController@tiempo'
]);

Route::get('tiempoAlumnoTarea/{dni}/{cod_tarea}', [
    'as' => 'tiempoAlumnoTarea',
    'middleware' => 'auth',
    'uses' => 'ServiciosController@tiempoAlumnoTarea'
]);

Route::get('crearTiempo/{id}', [
    'as' => 'crearTiempo',
    'middleware' => 'auth',
    'uses' => 'TiempoTareaController@create'
]);



// [amondejar]
// GAMIFICACIÓN //

Route::get('gestionPomodoro/{alumno_tarea_id}/{fase_actual}', [
    'as' => 'gestionPomodoro',
    'middleware' => 'auth',
    'uses' => 'MisTareasActivasController@gestionPomodoro'
]);

Route::get('avatar', [
    'as' => 'avatar',
    'middleware' => 'auth',
    'uses' => 'AvatarController@index'
]);

Route::get('aumentarNivelAvatar/{avatar_user_id}/{valor}', [
    'as' => 'aumentarNivelAvatar',
    'middleware' => 'auth',
    'uses' => 'AvatarController@aumentarNivelAvatar'
]);

Route::get('luchar', [
    'as' => 'luchar',
    'middleware' => 'auth',
    'uses' => 'AvatarController@luchar'
]);

Route::get('lucharContra/{avatar_user_id}', [
    'as' => 'lucharContra',
    'middleware' => 'auth',
    'uses' => 'AvatarController@lucharContra'
]);

Route::get('cambiarEstadoAvatar/{avatar_user_id}/{estado}', [
    'as' => 'cambiarEstadoAvatar',
    'middleware' => 'auth',
    'uses' => 'AvatarController@cambiarEstadoAvatar'
]);

Route::get('imagenAvatar/{user_id}/{parte}', [
    'as' => 'imagenAvatar',
    'middleware' => 'auth',
    'uses' => 'AvatarController@imagenAvatar'
]);

Route::get('mostrarModalFirstLogin', [
    'as' => 'mostrarModalFirstLogin',
    'middleware' => 'auth',
    'uses' => 'UserController@mostrarModalFirstLogin'
]);

Route::get('completarTarea/{alumno_tarea_id}', [
    'as' => 'completarTarea',
    'middleware' => 'auth',
    'uses' => 'TareaAlumnoController@completarTarea'
]);

Route::get('tienda', [
    'as' => 'tienda',
    'middleware' => 'auth',
    'uses' => 'TiendaController@index'
]);

Route::post('subirImagenAvatar', [
    'as' => 'subirImagenAvatar',
    'middleware' => 'auth',
    'uses' => 'TiendaController@subirImagenAvatar'
]);

Route::get('eliminarImagen/{imagen_id}', [
    'as' => 'eliminarImagen',
    'middleware' => 'auth',
    'uses' => 'TiendaController@eliminarImagen'
]);

Route::get('getImage/{filename}', [
    'as' => 'getImage',
    'middleware' => 'auth',
    'uses' => 'TiendaController@getImage'
]);

Route::get('comprarImagen/{imagen_id}', [
    'as' => 'comprarImagen',
    'middleware' => 'auth',
    'uses' => 'TiendaController@comprarImagen'
]);

Route::get('editarImagenAvatar', [
    'as' => 'editarImagenAvatar',
    'middleware' => 'auth',
    'uses' => 'AvatarImagenController@index'
]);

Route::get('cambiarImagenAvatar/{imagen_id}/{estado}', [
    'as' => 'cambiarImagenAvatar',
    'middleware' => 'auth',
    'uses' => 'AvatarImagenController@cambiarImagenAvatar'
]);
//[amondejar]



Route::post('crearTarea', 'ServiciosController@crearTarea');
Route::post('addAlumno', 'ServiciosController@addAlumno');