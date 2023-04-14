<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Services\AuthService;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $authService;

    function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        if($validator->fails())
            return response()->error([ 'message' => 'Validation Error.', 'statusCode' => 413, 'errors' => $validator->errors() ]);

        return (new AuthService())->register($request);
    }

    public function login(Request $request): JsonResponse
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
