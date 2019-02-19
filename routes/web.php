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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//TODO: what if I remove? anyways the default is apply web middleware for all routes
Route::group(['middleware' => ['web']], function () {
/*
    // Authentication Routes
    // Route::get('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
    Route::post('users/login', 'AuthApiController@login');
    Route::get('auth/logout', ['as' => 'logout', 'uses' => 'Auth\AuthApiController@getLogout']);

    // Registration Routes
    Route::get('auth/register', 'Auth\AuthApiController@getRegister');
    Route::post('users/register', 'Api\Http\Controllers\AuthApiController@register');
    Route::post('users', 'AuthApiController@register');

    // Password Reset Routes
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');


    // General routes
    Route::get('/', ['uses' => 'HomeController@index', 'as' => 'blog.index']);
    Route::get('about', 'HomeController@getAbout');

    // Articles
    Route::resource('articles', 'ArticleController');
    // Articles By slug
    Route::get('articles/{slug}', ['as' => 'blog.single', 'uses' => 'BlogController@getSingle'])->where('slug', '[\w\d\-\_]+');
    // Articles By category
    Route::get('blog/categories/{category}',
               ['as' => 'articles.category', 'uses' => 'ArticleController@getByCategory'])
         ->where('category', '[\w\d\-\_]+');

    // Articles by tag
    Route::get('articles/tags/{tag}',
               ['as' => 'articles.category', 'uses' => 'ArticleController@getByTag'])
         ->where('tag', '[\w\d\-\_]+');

    // Articles by author
    Route::get('articles/authors/{author}',
               ['as' => 'articles.author', 'uses' => 'ArticleController@getByAuthor']);//->where('author', '[\w\d\-\_]+');


    // Comments
    //Resource::resource('articles/{id}/comments', 'CommentController');
    Route::get('articles/{article_id}/comments', 'CommentController@index');

    Route::post('articles/{article_id}/comments/', ['uses' => 'CommentsController@store', 'as' => 'comments.store']);
    Route::get('articles/{article_id}/comments/{id}/edit', ['uses' => 'CommentsController@edit', 'as' => 'comments.edit']);
    Route::put('articles/{article_id}/comments/{id}', ['uses' => 'CommentsController@update', 'as' => 'comments.update']);
    Route::delete('articles/{article_id}/comments/{id}', ['uses' => 'CommentsController@destroy', 'as' => 'comments.destroy']);
    Route::get('articles/{article_id}/comments/{id}/delete', ['uses' => 'CommentsController@delete', 'as' => 'comments.delete']);

    // Admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.',
                     'middleware' => ['auth', 'role:admin']], // This is my RoleMiddleware
        function () {
            Route::get('dashboard', 'HomeController@index')->name('dashboard');
            Route::resource('articles', 'ArticleController');
            Route::resource('users', 'UserController');
            Route::resource('comments', 'CommentController');
        });
*/

});