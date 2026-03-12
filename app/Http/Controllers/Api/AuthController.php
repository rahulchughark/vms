<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
/**
 * @OA\Post(
 *     path="/api/register",
 *     tags={"Auth"},
 *     summary="Register user",
 *     @OA\Response(response=200, description="Registered")
 * )
 */
 public function register(Request $request)
    {
        // return response()->json(['message' => 'Registration is disabled.'], Response::HTTP_FORBIDDEN);
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|integer|in:1,2,3,4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Registered successfully',
            'token' => $token,
            'user' => $user,
        ], Response::HTTP_CREATED);
    }


/**
 * @OA\Post(
 *     path="/api/login",
 *     tags={"Auth"},
 *     summary="Login user",
 *     @OA\Response(response=200, description="Login success")
 * )
 */

public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email:rfc,dns',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        }

        $validated = $validator->validated();

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED); // 401
        }

        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user,
        ], Response::HTTP_OK);
    }

/**
 * @OA\Post(
 *     path="/api/logout",
 *     tags={"Auth"},
 *     summary="Logout user",
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(response=200, description="Logged out")
 * )
 */
public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ]);
    }



    


}
