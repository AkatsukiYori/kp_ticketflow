<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/new-ticket", function() {
    return view("frontend/new_ticket");
});
