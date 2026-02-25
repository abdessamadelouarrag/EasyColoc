<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('colocations', ColocationController::class);
});

Route::get('/deponse/create', function () {
    return view('deponse.deponse');
})->name('deponse.create');

Route::get('/colocation/create', function () {
    return view('colocation.colocation');
})->name('colocation.create');

Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/colocations/{colocation}/invitations/create', [InvitationController::class, 'create'])
        ->name('invitations.create');

    Route::post('/colocations/{colocation}/invitations', [InvitationController::class, 'store'])
        ->name('invitations.store');

    Route::get('/invitations/accept/{token}', [InvitationController::class, 'accept'])
        ->name('invitations.accept');
});
require __DIR__ . '/auth.php';
