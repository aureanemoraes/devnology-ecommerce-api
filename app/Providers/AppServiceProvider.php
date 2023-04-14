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
        Response::macro('success', function ($attrs) {
            $data = isset($attrs['data']) ? $attrs['data'] : null;
            $statusCode = isset($attrs['statusCode']) ? $attrs['statusCode'] : 200;
            $message = isset($attrs['message']) ? $attrs['message'] : 'Success.';

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => $message
            ], $statusCode);
        });

        Response::macro('error', function ($attrs) {
            $errors = isset($attrs['errors']) ? $attrs['errors'] : null;
            $statusCode = isset($attrs['statusCode']) ? $attrs['statusCode'] : 400;
            $message = isset($attrs['message']) ? $attrs['message'] : 'Error.';

            return response()->json([
                'success' => false,
                'errors' => $errors,
                'message' => $message
            ], $statusCode);
        });
    }
}
