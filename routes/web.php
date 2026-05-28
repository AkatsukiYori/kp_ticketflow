<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function() {
    return view("/frontend/user/index");
});

Route::get("/dashboard", function() {
    return view("/frontend/admin/dashboard");
});

Route::get("/ticket", function() {
    return view("/frontend/admin/ticket");
});

Route::get("/user", function() {
    return view("/frontend/admin/user");
});

Route::get("/report", function() {
    return view("/frontend/admin/report");
});

Route::get("/logout", function() {
    return view("/frontend/admin/logout");
});

Route::get("/logs", function() {
    return view("/frontend/admin/logs");
});

Route::get("/ikb", function() {
    return view("/frontend/admin/ikb");
});

Route::get("/documentation", function() {
    return view("/frontend/admin/documentation");
});

Route::get("/category", function() {
    return view("/frontend/admin/category");
});