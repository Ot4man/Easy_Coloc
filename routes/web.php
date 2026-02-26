<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvitationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/export', [AdminController::class, 'export'])->name('export');
        Route::post('/users/{user}/toggle-ban', [AdminController::class, 'toggleBan'])->name('users.toggle-ban');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/colocations', [ColocationController::class, 'index'])
        ->name('colocations.index');

    Route::get('/colocations/create', [ColocationController::class, 'create'])
        ->name('colocations.create');

    Route::post('/colocations', [ColocationController::class, 'store'])
        ->name('colocations.store');

    Route::get('/colocations/{colocation}', [ColocationController::class, 'show'])
        ->name('colocations.show');

    Route::put('/colocations/{colocation}', [ColocationController::class, 'update'])
        ->name('colocations.update');

    Route::delete('/colocations/{colocation}', [ColocationController::class, 'destroy'])
        ->name('colocations.destroy');

    Route::post('/colocations/{colocation}/cancel', [ColocationController::class, 'cancel'])
        ->name('colocations.cancel');

    Route::post('/colocations/{colocation}/leave', [ColocationController::class, 'leave'])
        ->name('colocations.leave');
});
Route::middleware('auth')->group(function () {

    // Categories
    Route::post('/colocations/{colocation}/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('/invitations/send', [InvitationController::class, 'send'])->name('invitations.send');
    Route::get('/invitations/accept/{token}', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/refuse/{token}', [InvitationController::class, 'refuse'])->name('invitations.refuse');

    Route::resource('expenses', ExpenseController::class)->except(['show']);

});
