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

Route::get('/', 'HomeController@index')->name('home');


Route::get('login', 'Auth\LoginController@showLoginForm')->name('showLogin');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');


Route::get('resumen', 'SummaryController@show')->name('summary');

Route::get('twitterengine', 'TwitterEngineController@show')->name('twitterEngine');
Route::post('twitterengine', 'TwitterEngineController@doTwitterEngine')->name('twitterEngineConf');

Route::get('statistics', 'StatisticsController@show')->name('statistics');
Route::post('statistics', 'StatisticsController@viewStatistics')->name('statisticsDone');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
