<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Services\AuthService;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\LoginRequest;

class AuthController extends Controller
{
    private $authService;

    function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return (new AuthService())->register($request);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails())
            return response()->error([ 'message' => 'Validation Error.', 'statusCode' => 413, 'errors' => $validator->errors() ]);

        return (new AuthService())->login($request);
    }
}
