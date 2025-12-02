<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;

// ============================
// HOME — FIX SESUAI SARAN
// ============================
Route::get('/', [EventController::class, 'index'])->name('home');

// ============================
// AUTH ROUTES (GUEST ONLY)
// ============================
Route::middleware('isGuest')->group(function () {

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [UserController::class, 'loginAuth'])->name('login.auth');

    Route::get('/sign-up', function () {
        return view('signup');
    })->name('sign_up');

    Route::post('/sign-up', [UserController::class, 'signUp'])->name('sign_up.add');
});

// ============================
// LOGOUT
// ============================
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// ============================
// DETAIL EVENT (tidak perlu login)
// ============================
Route::get('/events', [EventController::class, 'events'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'detail'])->name('events.detail');

// ============================
// PAYMENT ROUTES (LOGIN REQUIRED)
// ============================
Route::middleware('auth')->prefix('/payment')->name('payment.')->group(function () {

    Route::get('/form/{event_id}', [PaymentController::class, 'create'])->name('create');
    Route::post('/store', [PaymentController::class, 'store'])->name('store');

    Route::get('/success/{payment_id}', [PaymentController::class, 'success'])->name('success');
    Route::get('/history', [PaymentController::class, 'history'])->name('history');
    Route::get('/detail/{payment_id}', [PaymentController::class, 'show'])->name('show');
});

// ============================
// ADMIN ROUTES
// ============================
Route::middleware('isAdmin')->prefix('/admin')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // USERS (ADMIN)
    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', fn() => view('admin.user.create'))->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');

        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');

        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
        Route::get('/export', [UserController::class, 'export'])->name('export');

        Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [UserController::class, 'deletePermanent'])->name('delete_permanent');

        Route::get('/datatables', [UserController::class, 'datatables'])->name('datatables');
    });

    // EVENTS (ADMIN)
    Route::prefix('/events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'adminIndex'])->name('index');
        Route::get('/create', fn() => view('admin.event.create'))->name('create');
        Route::post('/store', [EventController::class, 'store'])->name('store');

        Route::get('/edit/{id}', [EventController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [EventController::class, 'update'])->name('update');

        Route::delete('/delete/{id}', [EventController::class, 'destroy'])->name('delete');
        Route::put('/nonaktived/{id}', [EventController::class, 'nonaktived'])->name('nonaktived');

        Route::get('/export', [EventController::class, 'export'])->name('export');
        Route::get('/trash', [EventController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [EventController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [EventController::class, 'deletePermanent'])->name('delete_permanent');

        Route::get('/datatables', [EventController::class, 'datatables'])->name('datatables');
    });
});
