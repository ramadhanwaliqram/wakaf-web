<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'message' => 'Successfully get data profile',
            'data' => $user->setHidden(['password', 'remember_token', 'role', 'email_verified_at', 'created_at', 'updated_at'])
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        //check password and password_confirmation are the same
        if ($request->password != $request->password_confirmation) {
            return response()->json([
                'success' => false,
                'message' => 'Password and password confirmation are not the same'
            ], 422);
        }

        //update password
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ], 201);
    }

    public function updateProfile(Request $request)
    {
        $only = ['name', 'address', 'phone'];

        $validator = Validator::make($request->only($only), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:13',
        ]);

        if ($validator->fails()) {
            return response()->json([
                $validator->errors()->first()
            ], 422);
        }

        $user = auth()->user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
        ], 201);
    }
}
