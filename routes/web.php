<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    // AUTH
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');

    // PAGES
    Route::prefix('pages')->name('pages.')->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.pages.dashboard');
        })->name('dashboard');

        Route::get('/category', function () {
            return view('admin.pages.category');
        })->name('category');

        Route::get('/ticket', function () {
            return view('admin.pages.ticket');
        })->name('ticket');

        Route::get('/documentation', function () {
            return view('admin.pages.documentation');
        })->name('documentation');

        Route::get('/ikb', function () {
            return view('admin.pages.ikb');
        })->name('ikb');

        Route::get('/logs', function () {
            return view('admin.pages.logs');
        })->name('logs');

        Route::get('/report', function () {
            return view('admin.pages.report');
        })->name('report');

        Route::get('/user', function () {
            return view('admin.pages.user');
        })->name('user');

        Route::get('/logout', function () {
            return view('admin.pages.logout');
        })->name('logout');

    });

});