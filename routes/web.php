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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();


/**
 * TestController
 */
Route::post('/test', 'TestController@store');
Route::get('/test/create', 'TestController@showCreateForm')->name('test.create');
Route::get('/test/{slug}', 'TestController@showCover');
Route::get('/', 'TestController@showList')->name('test.showList');
Route::get('/test/publish/{slug}', 'TestController@publish')->name('test.publish');
Route::get('/test/{slug}/questions', 'TestController@showQuestions');
Route::get('/test/answers/{slug}', 'TestController@showAnswers');

Route::get('/123', 'TestController@view');

/**
 * StatisticalController
 */
Route::post('/test/{slug}/questions', 'StatisticalController@completeTestPassing');
Route::post('/test/{slug}', 'StatisticalController@createAndSaveStatistic');
Route::get('/stats/test/{value}', 'StatisticalController@showStatisticsListInTest')->name('showStatisticsList');
Route::get('/stats/{id}', 'StatisticalController@showStatistic')->name('showStatistic');
Route::post('/check-if-statistics-created', 'StatisticalController@checkIfStatisticCreated');


Route::put('/user/update-email', 'UserController@updateEmail');

Route::get('/profile', 'ProfileController@show');
