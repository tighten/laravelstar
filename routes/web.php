<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('posts', function () {
    return App\Post::all();
});
