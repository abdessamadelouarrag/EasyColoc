<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::middleware(['auth', 'banned'])->group(function () {

    Route::get('/colocations/show', function () {

        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');

    Route::patch('/profile', function () {})->name('profile.update');

    Route::delete('/profile', function () {})->name('profile.destroy');

    Route::get('/colocations', [ColocationController::class, 'index'])->name('colocations.index');
    Route::get('/colocations/create', [ColocationController::class, 'create'])->name('colocations.create');
    Route::post('/colocations', [ColocationController::class, 'store'])->name('colocations.store');
    Route::get('/colocations/{colocation}', [ColocationController::class, 'show'])->name('colocations.show');
    Route::post('/colocations/{colocation}/cancel', [ColocationController::class, 'cancel'])->name('colocations.cancel');
    Route::post('/colocations/{colocation}/leave', [ColocationController::class, 'leave'])->name('colocations.leave');

    Route::post('/colocations/{colocation}/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('/invite/{token}', [InvitationController::class, 'acceptForm'])->name('invitations.form');
    Route::post('/invite/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invite/{token}/refuse', [InvitationController::class, 'refuse'])->name('invitations.refuse');

    Route::post('/colocations/{colocation}/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::post('/colocations/{colocation}/expenses', [ExpenseController::class, 'store'])->name('expenses.store');

    Route::post('/colocations/{colocation}/payments', [PaymentController::class, 'store'])->name('payments.store');

    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/users/{user}/toggle-ban', [AdminController::class, 'toggleBan'])->name('admin.toggleBan');
    });

    Route::get(
        '/colocations/{colocation}/expenses/create',
        [ExpenseController::class, 'create']
    )
        ->name('expenses.create');

    Route::get(
        '/colocations/{colocation}/invitations/create',
        [InvitationController::class, 'create']
    )
        ->name('invitations.create');

    Route::post('/colocations/{colocation}/categories', [CategoryController::class, 'store'])
        ->name('categories.store');
});

require __DIR__ . '/auth.php';
