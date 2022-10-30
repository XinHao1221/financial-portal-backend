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
        return $this->commonJsonResponse(
            [
                'token_type' => 'Bearer',
                'access_token' => $token,
            ],
            'User created successfully'
        );
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

        return $this->commonJsonResponse(
            [
                'token_type' => 'Bearer',
                'access_token' => $token,
            ],
            'Login Successfully'
        );
    }

    public function logout(Request $request)
    {
        // Remove access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Successfully'
        ]);
    }

    public function getCurrentUserDetails()
    {
        // Get current login user
        $user = Auth::user();

        return $this->commonJsonResponse($user);
    }
}
