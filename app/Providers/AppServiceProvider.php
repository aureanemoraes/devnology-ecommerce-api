<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data, $statusCode = 200) {
            return response()->json([
                'success' => true,
                'data' => $data
            ], $statusCode);
        });

        Response::macro('error', function ($error, $statusCode) {
            return response()->json([
                'success' => false,
                'error' => $error
            ], $statusCode);
        });
    }
}
