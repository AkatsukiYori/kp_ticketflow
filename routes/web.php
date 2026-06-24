<?php

use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\User\TicketController as UserTicket;

// Route::get('/', function () {
//     return view('user.pages.index');
// });

Route::get('/', function () {
    return view('user.pages.index');
})->name('home');

// ini yang baru, halaman create tiket user
Route::prefix('ticket')->name('ticket.')->group(function() {
    Route::get('/', [UserTicket::class, 'show'])->name('index');
    Route::post('/create', [UserTicket::class, 'create'])->name('create');
});

// AUTH
Route::get('/login', function () {
    return view('admin.auth.login');
})->name('index_view');

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    // PAGES
    Route::prefix('pages')->name('pages.')->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.pages.dashboard');
        })->name('dashboard');

        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', [CategoriesController::class, 'show'])->name('index');
            Route::get('/datatable', [CategoriesController::class, 'datatable'])->name('datatable');
            Route::post('/createOrUpdate', [CategoriesController::class, 'createOrUpdate'])->name('createOrUpdate');
            Route::get('/detail/{id}', [CategoriesController::class, 'detail'])->name('detail');
            Route::delete('/delete/{id}', [CategoriesController::class, 'delete'])->name('delete');
        });

        Route::prefix('ticket')->name('ticket.')->group(function() {
            Route::get('/', [TicketController::class, 'show'])->name('index');
            Route::get('/datatable', [TicketController::class, 'datatable'])->name('datatable');
            Route::get('/detail/{ticket_no}', [TicketController::class, 'detail'])->name('detail');
            Route::delete('/delete/{ticket_no}', [TicketController::class, 'delete'])->name('delete');
            Route::post('/assign/{ticket_no}', [TicketController::class, 'assign'])->name('assign');
            Route::post('/reject/{ticket_no}', [TicketController::class, 'reject'])->name('reject');
            Route::post('/feedback/{ticket_no}', [TicketController::class, 'feedback'])->name('feedback');
        });

        Route::prefix("documentation")->name("documentation.")->group(function() {
            Route::get("/", [DocumentationController::class, 'show'])->name('index');
            Route::get("/datatable", [DocumentationController::class, 'datatable'])->name('datatable');
            Route::post('/createOrUpdate', [DocumentationController::class, 'createOrUpdate'])->name('createOrUpdate');
            Route::get('/detail/{id}', [DocumentationController::class, 'detail'])->name('detail');
            Route::delete('/delete/{id}', [DocumentationController::class, 'delete'])->name("delete");
        });

        Route::get('/ikb', function () {
            return view('admin.pages.ikb');
        })->name('ikb');

        Route::prefix('logs')->name('logs.')->group(function() {
            Route::get('/', [LogController::class, 'show'])->name('index');
            Route::get('/datatable', [LogController::class, 'datatable'])->name('datatable');
            Route::get('/detail/{ticket_no}', [LogController::class, 'detail'])->name('detail');
        });

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
});

require __DIR__ . '/auth.php';
