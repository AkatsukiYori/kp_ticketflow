<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function() {
    return view("/frontend/user/index");
});

Route::get("/dashboard", function() {
    return view("/frontend/admin/dashboard");
});
