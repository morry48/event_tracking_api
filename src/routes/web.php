<?php

use Illuminate\Support\Facades\Route;


Route::get('/login', function () {
    return "login page";
});

Route::get('/tests', function () {
    return "'test' route works!";
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return "login page";
})->name('login');

