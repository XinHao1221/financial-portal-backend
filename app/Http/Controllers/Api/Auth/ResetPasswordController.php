<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if email exists
        $user = User::where('email', $request->email)->firstOrFail();

        // If user not registered
        // Return fake response 
        if (!$user) {
            return response()->json([
                'message' => 'Email to reset password sent successfully.',
            ]);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        $message = "";

        // Check if sent successfully
        $status === Password::RESET_LINK_SENT
            ? $message = "Email to reset password sent successfully!"
            : $message = "Email could not be sent to this email address";

        return response()->json([
            "message" => $message
        ]);
    }

    public function resetPassword(Request $request)
    {
        // Validate
        $request->validate([
            'email' => ["required", "email"],
            'password' => ["required", "confirmed"],
            'token' => ["required"]
        ]);

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();
            }
        );

        // Form response
        $message = "";
        $status == Password::PASSWORD_RESET ? $message = "Password updated successfully." : $message = "Fail to reset password.";

        return response()->json([
            "message" => $message
        ]);
    }
}
