<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::group(['middleware' => 'api'], function () {
Route::group(['middleware' => ['jwt.check']], function () {
    // ====================
    // == Authentication ==
    // ====================

    // Register
    Route::post('users/register', 'AuthApiController@register');
    Route::post('users', 'AuthApiController@register');
    Route::post('auth/register', 'AuthApiController@register');


    // Login
    Route::post('users/login', 'AuthApiController@login');
    Route::post('auth/login', 'AuthApiController@login');


    // Get/Update/Delete users
    Route::get('users', 'UserController@index');
    Route::get('user/{id}', 'UserController@show');
    Route::match(['put', 'patch'], 'user', 'UserController@update');


    // ====================
    // ==    Articles    ==
    // ====================
    // Route::resource('articles', 'ArticleController');
    Route::resource('articles', 'ArticleController', [
        'except' => [
            'create', 'edit'
        ]
    ]);
    Route::get('articles/by_id/{id}', 'ArticleController@getById');
    // Route::get('articles/feed', 'FeedController@index');
    Route::get('articles/by_author/{username}', 'ArticleController@getByAuthor');
    Route::get('articles/by_tag/{tag_name}', 'ArticleController@getByTag');
    Route::get('articles/by_category/{tag_name}', 'ArticleController@getByCategory');

    // User relations
    // Route::get('users/{user_id}', 'UserRelationsController@show');
    // Route::post('users/{user_id}/follow', 'UserRelationsController@follow');
    // Route::delete('users/{user_id}/follow', 'UserRelationsController@unFollow');

    // Like routes
    Route::get('likes', 'LikeController@index');
    Route::post('articles/{article}/likes', 'LikeController@like');
    Route::delete('articles/{article}/likes', 'LikeController@dislike');

    // Subscriptions
    Route::get('users/profile/following', 'SubscriptionController@following');
    Route::get('users/profile/followers', 'SubscriptionController@followers');

    Route::post('users/{user_id}/followers', 'SubscriptionController@subscribeToUser');
    Route::delete('users/{user_id}/followers', 'SubscriptionController@unsubscribeFromUser');

    /*
    // Comments
    Route::resource('articles/{slug}/comments', 'CommentController', [
        'only' => [
            'index', 'store', 'destroy'
        ]
    ]);*/

    Route::get('articles/{slug}/comments', 'CommentController@index');
    Route::resource('articles.comments', 'CommentController');

    Route::get('tags', 'TagController@index');
    Route::get('categories', 'CategoryController@index');

});


// jwt.auth middleware name must match http/Kernel.php

// TOdo: change notation
Route::middleware('jwt.auth')->get('/profile', function (Request $request) {
    return auth()->user();
});


// Todo: Exclude some resourc methods from jwt auth
Route::middleware('jwt.auth')->group(function () {
    //Route::resource('books', 'API\BookController') ;
    // Route::resource('books', API\BookController::class);
});