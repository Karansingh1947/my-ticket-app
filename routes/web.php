<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets (index, create, store = no visibility middleware)
    Route::resource('tickets', TicketController::class)->only(['index','create','store']);

    // Tickets (show, edit, update, destroy = with visibility middleware)
    Route::resource('tickets', TicketController::class)
        ->only(['show','edit','update','destroy'])
        ->middleware('ensure.ticket.visible');

    // Users (Admin only)
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])
        ->name('users.index');

});
require __DIR__.'/auth.php';
