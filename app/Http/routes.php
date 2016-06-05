<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::any('admin/login', 'Admin\LoginController@login');
    Route::get('admin/code', 'Admin\LoginController@code');

//});

//路由前缀,  对应的方法的命名空间 (简化路由分组)
Route::group(['middleware' => ['admin.login'],'prefix'=>'admin', 'namespace' => 'Admin'], function () {

    Route::get('index', 'IndexController@index');
    Route::get('info', 'IndexController@info');
    Route::get('quit', 'LoginController@quit');
    Route::any('pass', 'IndexController@pass');

    Route::post('cate/changeorder', 'CategoryController@changeorder');

    Route::resource('category', 'CategoryController');

});



