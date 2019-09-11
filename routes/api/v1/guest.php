<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get(
    '/poke',
    function (Request $request)
    {
        return "I'm alive!";
    }
);
