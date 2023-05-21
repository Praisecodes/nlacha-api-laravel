<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Login extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required | min:3',
            'password' => 'required | min:6'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Validation Error',
                'status' => 400,
                'error' => $validator->errors()
            ], 400);
        }

        if (User::where('username', $request->username)->doesntExist()) {
            return response([
                'message' => 'User Not Found',
                'status' => 404
            ], 404);
        }

        if (!Hash::check($request->password, User::where('username', $request->username)->value('password'))) {
            User::where('username', $request->username)->first()->tokens()->delete();
            return response([
                'message' => 'Invalid Credentials',
                'status' => 401
            ], 401);
        }

        User::where('username', $request->username)->first()->tokens()->delete();

        return response([
            'message' => 'success',
            'status' => 200,
            'user' => User::where('username', $request->username)->first(),
            'token' => ((User::where('username', $request->username)->value('role') == 'admin')?
                User::where('username', $request->username)->first()->createToken('API_TOKEN', ['admin:roles'])->plainTextToken
                :
                User::where('username', $request->username)->first()->createToken('API_TOKEN', ['user:role'])->plainTextToken
            )
        ], 200);
    }
}
