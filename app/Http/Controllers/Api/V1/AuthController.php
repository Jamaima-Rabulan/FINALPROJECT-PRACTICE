<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Requests\Api\RegisterUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Gives this controller the ok(), success() and error() response helpers
    use ApiResponses;

    // User Registration: creates the account and logs the user in immediately
    public function register(RegisterUserRequest $request)
    {
        // Create the user. The password is hashed (bcrypt) before it is stored.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate a JWT for the new user using the "api" guard
        $token = Auth::guard('api')->login($user);

        return $this->ok('Registered successfully', $this->formatToken($token));
    }

    // Login: checks the credentials and returns a token when they are correct
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // attempt() returns false when the email or password is wrong
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return $this->error('Invalid credentials', 401);
        }

        return $this->ok('Login successful', $this->formatToken($token));
    }

    // Verifying/reading an existing token: returns the user that owns the Bearer token
    public function me()
    {
        return $this->ok('User retrieved', Auth::guard('api')->user());
    }

    /**
     * Maps the raw token string to access_token, marks it as a bearer token,
     * and calculates its lifespan (expires_in) in seconds from the configured
     * Time-To-Live (TTL) in minutes. This is the standard OAuth 2.0 format,
     * so frontend and mobile clients can read the token easily.
     */
    protected function formatToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ];
    }

    // Revoking Authentication Tokens (invalidating/blacklisting):
    // refresh() blacklists the current token and returns a brand-new one
    public function refresh()
    {
        return $this->ok('Token refreshed', $this->formatToken(Auth::guard('api')->refresh()));
    }

    // Logout: blacklists the current token so it can never be used again
    public function logout()
    {
        Auth::guard('api')->logout();

        return $this->ok('Successfully logged out', null);
    }
}