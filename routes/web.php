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

Route::group(['middleware' => ['web']], function(){
    // Blog
    Route::get('blog/{slug}', ['uses' => 'BlogController@getSingle', 'as' => 'blog.single'])->where('slug', '[\w\d\-\_]+');
    Route::get('blog', ['uses' => 'BlogController@getIndex', 'as' => 'blog.index']);

    // Categories
    Route::resource('categories', 'CategoryController', ['except' => ['create']]);

    // Tags
    Route::resource('tags', 'TagController', ['except' => ['create']]);

    // Pages
    Route::get('/', 'PagesController@index');
    Route::get('about', 'PagesController@about');
    Route::get('contact', 'PagesController@getContact');
    Route::post('contact', 'PagesController@postContact');
    Route::resource('posts', 'PostController');
});

Auth::routes();
