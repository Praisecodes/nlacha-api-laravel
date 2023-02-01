<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Response;
use App\Models\UsersModels;

class LoginController extends Controller
{
    public function checkUser(LoginRequest $loginRequest){
        $loginRequest->validated();

        $details = UsersModels::where('username', $loginRequest->username);
        //Check if the user exists
        if(!$details->exists()){
            return Response(null, 404);
        }

        // Check if values match each other
        if(!password_verify($loginRequest->password, $details->value('userPassword')) || !($loginRequest->username == $details->value('username'))){
            return Response::json([
                'message' => "Invalid username or password"
            ], 400);
        }

        return Response::json($details->first(), 200);
    }
}
