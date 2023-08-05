<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $only = ['name', 'email', 'password', 'password_confirmation', 'role', 'address', 'phone'];

        $data = $request->only($only);

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:13',
        ]);

        if ($validator->fails()) {
            return response()->json([
                $validator->errors()->first()
            ], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'address' => $data['address'],
            'phone' => $data['phone'],
        ]);

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'User registered successfully.'
        ], 201);
    }

    public function login(Request $request)
    {
        $only = ['email', 'password'];
        $credentials = $request->only($only);

        $exists = User::where('email', $credentials['email'])->exists();

        if (!$exists) {
            return response()->json(['User not found', 404]);
        }

        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            //revoke all tokens
            $userTokens = $user->tokens;

            foreach ($userTokens as $token) {
                $token->revoke();
            }

            $token = $user->createToken('auth_token')->accessToken;

            $result['user'] = $user->makeHidden('tokens');
            $result['token'] = $token;

            return response()->json(['success' => true, 'message' => 'User logged in successfully', 'data' => $result], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout()
    {
        //revoke the token that was used to authenticate the current request...
        try {
            auth()->user()->token()->revoke();
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
}
