<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Manage authentication for the API
 */
class AuthController extends Controller
{

    /**
     * Create a user and return a token
     */
    public function register(Request $request)
    {
        try {
            Log::Debug('Api/Auth@register');

            $validator = Validator::make($request->all(), [
                "name" => 'required|string|max:128',
                "email" => 'required|string|max:128|email|unique:users,email',
                "password" => 'required|string|max:128|min:6',
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('Api/Auth@register validation failed', $data);

                return response()->json($data, 422);
            }

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            $user->save();

            $token = $user->createToken('trello-register-' . $request->email)->plainTextToken;

            $data = [
                'status' => 201,
                'user' => $user,
                'token' => $token,
            ];
            Log::Debug('Api/Auth@register user ' . $user->name . ' registerd', $data);
            return response()->json($data, 201);

        } catch (\Exception $e) {

            Log::Error('Api/Auth@store', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
            ];

            return response()->json($data, 500);
        }
    }

    /**
     * Return a token for a user
     */
    public function login(Request $request)
    {
        try {
            Log::Debug('Api/Auth@login');

            $validator = Validator::make($request->all(), [
                "email" => 'required|string|max:128|email',
                "password" => 'required|string|max:128',
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Login validation failed',
                ];
                Log::Debug('Api/Auth@login validation failed', $data);

                return response()->json($data, 422);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                $data = [
                    'status' => 401,
                    'message' => 'Unauthorized',
                ];
                Log::Debug('Api/Auth@login Unauthorized ' . $request->email, $data);

                return response()->json($data, 401);
            }

            $token = $user->createToken('trello-login-' . $request->email)->plainTextToken;

            $data = [
                'status' => 201,
                'board' => $user,
                'token' => $token,
            ];
            Log::Debug('Api/Auth@login ' . $user->email . " succesful login");
            return response()->json($data, 201);

        } catch (\Exception $e) {

            Log::Error('Api/Auth@store', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
            ];

            return response()->json($data, 500);
        }
    }

    /**
     * Invalidate the user token
     */
    public function logout(Request $request)
    {
        try {
            $name = auth()->user()->name;
            Log::Debug('Api/Auth@logout ' . $name);

            auth()->user()->tokens()->delete();

            return response()->json(['message' => $name . ' Logged out'], 200);

        } catch (\Exception $e) {

            Log::Error('Api/Auth@store', ['message' => $e->getMessage()]);
            $data = [
                'status' => 500,
                'error' => 'Internal Server Error',
            ];

            return response()->json($data, 500);
        }
    }
}
