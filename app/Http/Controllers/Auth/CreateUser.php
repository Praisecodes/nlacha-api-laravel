<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required | min:3',
            'username' => 'required | min:3',
            'email' => 'required | email',
            'password' => 'required | min:6',
            'role' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Validation Error',
                'error' => $validator->errors(),
                'status' => 400
            ], 400);
        }

        if (User::where('email', $request->email)->exists() || User::where('username', $request->username)->exists()) {
            return response([
                'message' => 'User Already Exists',
                'status' => 409
            ], 409);
        }

        try {
            $addQuery = User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role == 'admin' ? $request->role : 'user',
            ]);

            return response([
                'message' => 'success',
                'status' => 200,
                'user' => User::where('email', $request->email)->first(),
                'token' => (
                    (User::where('email', $request->email)->value('role') == 'admin')
                    ? User::where('email', $request->email)->first()->createToken('API_TOKEN', ['admin:roles'])->plainTextToken
                    :
                    User::where('email', $request->email)->first()->createToken('API_TOKEN', ['user:role'])->plainTextToken
                )
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => 'Error Creating User',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
