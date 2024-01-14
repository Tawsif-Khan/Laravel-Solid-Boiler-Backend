<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthenticationController extends Controller
{
    public function store(LoginRequest $request)
    {
        try {

            $user = $request->authenticate();

            // $user->tokens()->delete();

            $token = $user->createToken($request->validated('email'))->plainTextToken;

            return response()->json(
                [
                    'message' => 'Login successful.',
                    'token' => $token,
                    'user' => $user,
                ],
                Response::HTTP_OK
            );

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'message' => $th->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function destroy(Request $request)
    {
        try {

            $request->user()->tokens()->delete();

            return response()->json(
                [
                    'message' => 'Logout successful.',
                ],
                Response::HTTP_OK
            );

        } catch (\Throwable $th) {

            return response()->json(
                [
                    'message' => $th->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
