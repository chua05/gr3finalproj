<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BorrowBookController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AdminController;

// Public Routes
Route::get('/status', function () {
    return response()->json([
        'status' => 'operational',
        'version' => '1.0.0',
        'timestamp' => now()->toDateTimeString(),
    ]);
});

Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->noContent();
});

Route::post('/auth/change-password', [UserController::class, 'changePassword']);

// Authenticated Routes
Route::middleware(['auth:sanctum'])->group(function () {

    // USER ROUTES
    Route::prefix('users')->group(function () {
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::get('/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // BOOK ROUTES (For all authenticated users)
    Route::prefix('books')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('books.index');
        Route::get('/{book}', [BookController::class, 'show'])->name('books.show');
        
        // Borrowing books
        Route::post('/{book}/borrow', [BorrowBookController::class, 'borrow'])->name('books.borrow');
    });

    // ADMIN ROUTES
    Route::prefix('admin')->group(function () {
        // Dashboard Stats
        Route::get('/dashboard-stats', [AdminController::class, 'dashboardStats'])->name('admin.dashboard.stats');

        // Book Management (for Admin)
        Route::prefix('books')->group(function () {
            Route::get('/', [BookController::class, 'adminIndex'])->name('admin.books.index');
            Route::post('/', [BookController::class, 'store'])->name('admin.books.store');
            Route::get('/{book}', [BookController::class, 'show'])->name('admin.books.show');
            Route::put('/{book}', [BookController::class, 'update'])->name('admin.books.update');
            Route::delete('/{book}', [BookController::class, 'destroy'])->name('admin.books.destroy');
        });

        // User Management (for Admin) — Already covered under /users
    });
});

