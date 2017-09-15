<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
    Route::get('/', function () {
        return App\Post::all();
    })->name('index');
});
