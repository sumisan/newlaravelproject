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

//got it here
Route::get('/home', 'HomeController@index')->name('home');


//Route::get('/admin', 'AdminUsersController@index');

Route::group(['middleware'=>'admin'], function(){

    Route::resource('/admin/users', 'AdminUsersController');

    //access posts page as an admin
    Route::resource('/admin/posts', 'AdminPostsController');

});