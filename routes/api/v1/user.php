<?php

use Illuminate\Support\Facades\Route;

Route::get('/users/{user}', ['uses' => 'UsersController@show']);

Route::get('/comments', ['uses' => 'CommentsController@index']);
Route::post('/comments', ['uses' => 'CommentsController@store']);
Route::get('/comments/{comment}', ['uses' => 'CommentsController@show']);
Route::put('/comments/{comment}', ['uses' => 'CommentsController@update']);
Route::delete('/comments/{comment}', ['uses' => 'CommentsController@destroy']);

//Route::match(['get', 'post], '/broadcasting/auth', ['uses' => 'BroadcastController@authenticate']);
