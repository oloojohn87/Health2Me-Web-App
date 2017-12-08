<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function() {
    return View::make('hello');
});

Route::get('authors2', function(){
	return View::make('authors.index');
});

Route::get('ajax', function(){
		$var = include('/var/www/html/classes_php/getDoctorStatsClass.php');
		
		$class = new getDoctorStatsClass($_GET['med_id']);
	
	return $_GET['med_id'];
});

Route::get('newrout', function(){
	return 'This is a new route';
});

Route::group(array('prefix' => 'ajax'), function() {
    Route::controller('doctors', 'DoctorStatsController');
});

Route::controller('authors', 'AuthorsController');
Route::controller('patients', 'PatientsController');
// =============================================
// CATCH ALL ROUTE =============================
// =============================================
// all routes that are not home or api will be redirected to the frontend
// this allows angular to route them
App::missing(function($exception)
{
	return View::make('hello');
});
