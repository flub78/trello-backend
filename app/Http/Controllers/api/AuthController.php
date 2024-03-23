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

            $token = $user->createToken('trello-api-access')->plainTextToken;

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
                "name" => 'required|string|max:128',
                "email" => 'required|string|max:128|email',
                "password" => 'required|string|max:128',
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('Api/Auth@login validation failed', $data);

                return response()->json($data, 422);
            }

            $element = new User;
            $element->name = $request->name;
            $element->email = $request->email;
            $element->password = Hash::make($request->password);

            $element->save();

            $data = [
                'status' => 201,
                'board' => $element,
            ];
            Log::Debug('Api/Auth@store saved in database', $data);
            return response()->json($element, 201);

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
            Log::Debug('Api/Auth@logout');

            $validator = Validator::make($request->all(), [
                "name" => 'required|string|max:128',
                "email" => 'required|string|max:128|email',
                "password" => 'required|string|max:128',
            ]);

            if ($validator->fails()) {
                $data = [
                    'status' => 422,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed',
                ];
                Log::Debug('Api/Auth@logout validation failed', $data);

                return response()->json($data, 422);
            }

            $element = new User;
            $element->name = $request->name;
            $element->email = $request->email;
            $element->password = Hash::make($request->password);

            $element->save();

            $data = [
                'status' => 201,
                'board' => $element,
            ];
            Log::Debug('Api/Auth@store saved in database', $data);
            return response()->json($element, 201);

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
