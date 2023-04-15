<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;

class AuthService {
    public function register(RegisterRequest $request): JsonResponse
    {
        $inputs = $this->treatData($request);
        $user = User::create($inputs);
        $data = [ 'token' =>  $user->createToken('MyApp')->plainTextToken, 'user' => $user ];

        return response()->success([ 'data' => $data, 'statusCode' => 201, 'message' => "User register successfully." ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = $request->user();

            $data = [ 'token' => $user->createToken('MyApp')->plainTextToken, 'user' => new UserResource($user) ];

            return response()->success([ 'data' => $data, 'message' => 'User login successfully.' ]);
        } else
            return response()->error([ 'statusCode' => '413', 'message' =>  'Unauthorised.']);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->success([ 'statusCode' => 204 ]);
    }

    private function treatData(Request $request) {
        $dataTreated = $request->all();
        $dataTreated['password'] = bcrypt($dataTreated['password']);

        return $dataTreated;
    }

}
