<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\UserExistException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        // retrieved validated user
        $validated = $request->validated();

        // Encrypt user password
        $validated["password"] = Hash::make($validated["password"]);

        // create new user
        $user = User::create($validated);

        event(new Registered($user));

        // Generate auth token
        $token = $this->createToken($user);

        // Return token
        return response()->json([
            "message" => "User created successfully",
            "data" => [
                "token_type" => "Bearer",
                'access_token' => $token,
            ]
        ]);
    }

    public function createToken($user)
    {
        // Generate auth token
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function login(LoginRequest $request)
    {

        $validated = $request->validated();

        // Try login
        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => "Invalid login details",
            ], 401);
        }

        // If login success
        // Get user details
        $user = User::where('email', $validated['email'])->firstOrFail();

        // Generate token
        $token = $this->createToken($user);

        return response()->json([
            "data" => [
                "token_type" => "Bearer",
                'access_token' => $token,
            ]
        ]);
    }

    public function getCurrentUserDetails()
    {
        // Get current login user
        $user = Auth::user();

        return response()->json([
            "user" => $user,
        ]);
    }
}
