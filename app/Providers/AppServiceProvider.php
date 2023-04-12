<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Illuminate\Http\Response;

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
        Response::macro('success', function ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        });

        Response::macro('error', function ($error, $statusCode) {
            return response()->json([
                'success' => false,
                'error' => $error
            ], $statusCode);
        });
    }
}
