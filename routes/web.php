<?php

use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DocumentationController;

Route::get('/', function() {
    return view('user.pages.index');
});

Route::get('/', function () {
    return view('user.pages.index');
});

// AUTH
Route::get('/login', function () {
    return view('admin.auth.login');
})->name('index_view');

Route::prefix('admin')->name('admin.')->group(function () {
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

        Route::prefix("documeentation")->name("documentation.")->group(function() {
            Route::get("/", [DocumentationController::class, 'show'])->name('index');
            Route::get("/datatable", [DocumentationController::class, 'datatable'])->name('datatable');
            Route::post('/createOrUpdate', [DocumentationController::class, 'createOrUpdate'])->name('createOrUpdate');
            Route::get('/detail/{id}', [DocumentationController::class, 'detail'])->name('detail');
            Route::delete('/delete/{id}', [DocumentationController::class, 'delete'])->name("delete");
        });

        Route::get('/ikb', function () {
            return view('admin.pages.ikb');
        })->name('ikb');

        Route::get('/logs', function () {
            return view('admin.pages.logs');
        })->name('logs');

        Route::get('/report', function () {
            return view('admin.pages.report');
        })->name('report');

        Route::prefix('member')->name('member.')->group(function() {
            Route::get('/', [MemberController::class, 'show'])->name('index');
            Route::get('/datatable', [MemberController::class, 'datatable'])->name('datatable');
            Route::post('/createOrUpdate', [MemberController::class, 'createOrUpdate'])->name('createOrUpdate');
            Route::get('/detail/{id}', [MemberController::class, 'detail'])->name('detail');
            Route::delete('/delete/{id}', [MemberController::class, 'delete'])->name('delete');
        });

        Route::get('/', function () {
            return view('user.pages.index');
        })->name('index');
    });
})->middleware('auth');

require __DIR__.'/auth.php';
