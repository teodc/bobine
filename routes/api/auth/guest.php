<?php

use Illuminate\Support\Facades\Route;

Route::post('/tokens', ['uses' => 'TokensController@store']);
