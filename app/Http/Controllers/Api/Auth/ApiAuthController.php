<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        // validate the login request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // authentication successful, issue new API token
            $token = Auth::user()->createToken('api-token')->plainTextToken;
            return response()->json(['token' => $token]);
        } else {
            // authentication failed, return error message
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function register(Request $request)
    {
        // validate the registration request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // issue new API token for the user
        $token = $user->createToken('api-token')->plainTextToken;

        // return the new user and token
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function refresh(Request $request)
    {
        $newToken = Auth::user()->tokens()->create(['name' => 'new-token'])->plainTextToken;
        Auth::user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->delete();

        return response()->json(['token' => $newToken]);
    }

    public function user(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
}
