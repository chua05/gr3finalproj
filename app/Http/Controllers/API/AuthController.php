<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class AuthController extends Controller
{
    /**
     * Register a new user and issue a token.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
    
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user', // Default to 'user' role
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    /**
     * Login a user with email and password and return a token.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // Rate limiting check: Allow max 5 attempts per minute per IP
        if (RateLimiter::tooManyAttempts('login:'.$request->ip(), 5)) {
            return response()->json([
                'message' => 'Too many login attempts. Please try again later.'
            ], 429);
        }

        // Attempt login
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Increment failed attempts if login fails
            RateLimiter::hit('login:'.$request->ip(), 60); // 60 seconds decay time
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        // Clear rate limiter on successful login
        RateLimiter::clear('login:'.$request->ip());

        // Retrieve user and generate token
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    /**
     * Get the authenticated user details.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role
            ]
        ]);
    }

    /**
     * Logout the authenticated user and revoke their token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // Delete current token
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Send a password reset link to the user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Rate limiting
        if (RateLimiter::tooManyAttempts('password-reset:'.$request->ip(), 5)) {
            return response()->json([
                'message' => 'Too many password reset attempts. Please try again later.'
            ], 429);
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            RateLimiter::hit('password-reset:'.$request->ip(), 60);
            return response()->json([
                'message' => 'If this email exists in our system, a reset link has been sent.'
            ]);
        }

        // Generate password reset token
        $token = Password::createToken($user);

        // Send email with reset link (using Laravel's built-in notification system)
        $user->notify(new ResetPasswordNotification($token));

        // Clear rate limiter on successful request
        RateLimiter::clear('password-reset:'.$request->ip());

        return response()->json([
            'message' => 'If this email exists in our system, a reset link has been sent.'
        ]);
    }

    /**
     * Reset the user's password.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:8'
        ]);

        // Rate limiting
        if (RateLimiter::tooManyAttempts('password-reset:'.$request->ip(), 5)) {
            return response()->json([
                'message' => 'Too many password reset attempts. Please try again later.'
            ], 429);
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            RateLimiter::hit('password-reset:'.$request->ip(), 60);
            return response()->json([
                'message' => 'Invalid token or email.'
            ], 400);
        }

        // Validate the token
        if (!Password::tokenExists($user, $request->token)) {
            return response()->json([
                'message' => 'Invalid token or email.'
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear rate limiter on successful reset
        RateLimiter::clear('password-reset:'.$request->ip());

        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }
}
