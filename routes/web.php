<?php

use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\IkbController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\User\CekTicketController;
use App\Http\Controllers\User\TicketController as UserTicket;
use App\Http\Controllers\UploadController;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

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
    Route::post('/temp', [UploadController::class, 'uploadTemp'])->name('uploadTemp');
    Route::post('/revert', [UploadController::class, 'uploadRevert'])->name('uploadRevert');
});

//halaman cek ticket user
Route::prefix('cek-status')->name('cek_status.')->group(function() {
    Route::get('/', [CekTicketController::class, 'show'])->name('index');
    Route::get('/detail/{ticket_no}', [CekTicketController::class, 'detail'])->name('detail');
    Route::get('/search', [CekTicketController::class, 'filter'])->name('filter');
    Route::get('/log/{ticket_no}', [CekTicketController::class, 'log'])->name('log');
    Route::post('/respon/{ticket_no}', [CekTicketController::class, 'respon'])->name('respon');
    Route::post('/closed/{ticket_no}', [CekTicketController::class, 'closed'])->name('closed');
    Route::post('/rating/{ticket_no}', [CekTicketController::class, 'rating'])->name('rating');
    Route::post('/open-ticket/{ticket_no}', [CekTicketController::class, 'openTicket'])->name('open');
});

// AUTH
Route::get('/login', function () {
    return view('admin.auth.login');
})->name('index_view');

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    // PAGES
    Route::prefix('pages')->name('pages.')->group(function () {

        Route::prefix('dashboard')->name('dashboard.')->group(function() {
            Route::get('/', [DashboardController::class, 'show'])->name('index');
            Route::get('/datatable', [DashboardController::class, 'datatable'])->name('datatable');
            Route::get('/filter', [DashboardController::class, 'filter'])->name('filter');
        });

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

        Route::prefix('ikb')->name('ikb.')->group(function() {
            Route::get('/', [IkbController::class, 'show'])->name('index');
            Route::get('/datatable', [IkbController::class, 'datatable'])->name('datatable');
            Route::get('/detail/{ticket_no}', [IkbController::class, 'detail'])->name('detail');
            Route::delete('/delete/{ticket_no}', [IkbController::class, 'delete'])->name('delete');
            Route::post('/assign/{ticket_no}', [IkbController::class, 'assign'])->name('assign');
            Route::post('/reject/{ticket_no}', [IkbController::class, 'reject'])->name('reject');
            Route::post('/feedback/{ticket_no}', [IkbController::class, 'feedback'])->name('feedback');
            Route::post('/update/{ticket_no}', [IkbController::class, 'update'])->name('update');
        });

        Route::prefix('logs')->name('logs.')->group(function() {
            Route::get('/', [LogController::class, 'show'])->name('index');
            Route::get('/datatable', [LogController::class, 'datatable'])->name('datatable');
            Route::get('/detail/{ticket_no}', [LogController::class, 'detail'])->name('detail');
        });

        Route::prefix('report')->name('report.')->group(function() {
            Route::get('/', [ReportController::class, 'show'])->name('index');
            Route::get('/filter', [ReportController::class, 'filter'])->name('filter');
        });

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
