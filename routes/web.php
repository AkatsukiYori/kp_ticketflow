<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;

Route::get('/', function() {
    return view('user.pages.index');
});

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

        Route::prefix('category')->name('category.')->group(function() {
            Route::get('/', [CategoriesController::class, 'show'])->name('index');
            Route::get('/datatable', [CategoriesController::class, 'datatable'])->name('datatable');
            Route::post('/createOrUpdate', [CategoriesController::class, 'createOrUpdate'])->name('createOrUpdate');
            Route::get('/detail/{id}', [CategoriesController::class, 'detail'])->name('detail');
            Route::delete('/delete/{id}', [CategoriesController::class, 'delete'])->name('delete');
        });

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

        Route::prefix('user')->name('user.')->group(function() {
            Route::get('/', [UserController::class, 'show'])->name('index');
            Route::get('/datatable', [UserController::class, 'datatable'])->name('datatable');
            Route::post('/createOrUpdate', [UserController::class, 'createOrUpdate'])->name('createOrUpdate');
            Route::get('/detail/{id}', [UserController::class, 'detail'])->name('detail');
            Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('delete');
        });

        Route::get('/', function () {
            return view('user.pages.index');
        })->name('index');

    });

});