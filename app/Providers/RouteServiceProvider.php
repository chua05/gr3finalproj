<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Book;
use App\Models\BorrowBook;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */





    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    
    // Add explicit model binding
    Route::bind('Book', function ($value) {
        return \App\Models\Book::find($value) ?? abort(404, 'Book not found');
    });

    Route::bind('BorrowBook', function ($value) {
        return \App\Models\BorrowBook::find($value) ?? abort(404, 'Borrow book not found');
    });

    $this->routes(function () {
        Route::prefix('api')
            ->middleware('api')
            ->namespace('App\Http\Controllers')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    });
}

    /**
     * Configure the model bindings.
     */
    protected function configureModelBindings(): void
    {
        // Explicit binding for Book model
        Route::model('book', Book::class);
        
        // Or use a custom resolver with better error handling:
        Route::bind('book', function ($value) {
            $book = Book::find($value);
            
            if (!$book) {
                abort(404, 'Book are not found');
            }
            
            return $book;
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
