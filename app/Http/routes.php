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


Route::get('/', 'Home\IndexController@index');
Route::get('/cate/{cate_id}', 'Home\IndexController@cate');
Route::get('/a/{art_id}', 'Home\IndexController@article');

Route::any('admin/login', 'Admin\LoginController@login');
Route::get('admin/code', 'Admin\LoginController@code');

//路由前缀,  对应的方法的命名空间 (简化路由分组)
Route::group(['middleware' => ['admin.login'],'prefix'=>'admin', 'namespace' => 'Admin'], function () {

    Route::get('index', 'IndexController@index');
    Route::get('info', 'IndexController@info');
    Route::get('quit', 'LoginController@quit');
    Route::any('pass', 'IndexController@pass');

    Route::post('cate/changeorder', 'CategoryController@changeorder');

    Route::resource('category', 'CategoryController');
    Route::resource('article', 'ArticleController');

    Route::post('links/changeorder', 'LinksController@changeOrder');
    Route::resource('links', 'LinksController');

    Route::post('navs/changeorder', 'NavsController@changeOrder');
    Route::resource('navs', 'NavsController');

    Route::get('config/putfile', 'ConfigController@putFile');
    Route::post('config/changecontent', 'ConfigController@changeContent');
    Route::post('config/changeorder', 'ConfigController@changeOrder');
    Route::resource('config', 'ConfigController');

    Route::any('upload', 'CommonController@upload');

});

Route::any('admin/vote','Admin\VoteController@test');

Route::group(['prefix' => 'ajax', 'namespace' => 'Admin'], function(){

    Route::post('like', 'VoteController@like');
    Route::post('hate', 'VoteController@hate');

});
