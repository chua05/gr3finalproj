<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BorrowBookController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AdminController;

// Root API route (no auth)
Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API root',
        'status' => 'success',
        'timestamp' => now()->toDateTimeString(),
    ]);
});

// Public Routes (no auth)
Route::get('/status', function () {
    return response()->json([
        'status' => 'operational',
        'version' => '1.0.0',
        'timestamp' => now()->toDateTimeString(),
    ]);
});

// 🔍 Add this to list available public endpoints
Route::get('/endpoints', function () {
    return response()->json([
        'available_endpoints' => [
            'GET /api/status',
            'GET /api/endpoints',
            'POST /api/auth/change-password',
            'GET /api/books',
            'POST /api/books/{book}/borrow',
            'PUT /api/users/{user}',
            'GET /api/users/{user}',
            'DELETE /api/users/{user}',
            'GET /api/admin/dashboard-stats',
            'POST /api/admin/books',
            'PUT /api/admin/books/{book}',
            'DELETE /api/admin/books/{book}',
        ]
    ]);
});

// Public book listing route (no auth) - for testing GET /api/books
Route::get('/books', [BookController::class, 'index']);

// Sanctum CSRF cookie route
Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->noContent();
});

// Public RESTful book routes (no auth)
Route::apiResource('books', BookController::class);

// Auth routes (no middleware)
Route::post('/auth/change-password', [UserController::class, 'changePassword']);

// Public user routes (no auth)
Route::get('/users', [UserController::class, 'index']);   // List all users
Route::post('/users', [UserController::class, 'store']);  // Create a new user

// Routes requiring authentication
Route::middleware(['auth:sanctum'])->group(function () {

    // USER ROUTES
    Route::prefix('users')->group(function () {
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // BOOK ROUTES (Authenticated users)
    Route::prefix('books')->group(function () {
        // Already have public GET /books
        Route::get('/{book}', [BookController::class, 'show'])->name('books.show');
        
        // Borrowing books
        Route::post('/{book}/borrow', [BorrowBookController::class, 'borrow'])->name('books.borrow');
    
    });

    // ADMIN ROUTES
    Route::prefix('admin')->group(function () {
        // Dashboard Stats
        Route::get('/dashboard-stats', [AdminController::class, 'dashboardStats'])->name('admin.dashboard.stats');

        // Admin Book Management
        Route::prefix('books')->group(function () {
            Route::get('/', [BookController::class, 'index'])->name('admin.books.index'); // changed from adminIndex
            Route::post('/', [BookController::class, 'store'])->name('admin.books.store');
            Route::get('/{book}', [BookController::class, 'show'])->name('admin.books.show');
            Route::put('/{book}', [BookController::class, 'update'])->name('admin.books.update');
            Route::delete('/{book}', [BookController::class, 'destroy'])->name('admin.books.destroy');
        });

        // User Management (for Admin) — Already covered under /users
    });
});

