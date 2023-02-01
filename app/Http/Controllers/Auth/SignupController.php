<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\SignupRequest;
use App\Models\UsersModels;

class SignupController extends Controller
{
    public function signupUser(SignupRequest $signupRequest){
        $signupRequest->validated();

        //Check if user exists
        if(UsersModels::where('email', $signupRequest->email)->exists()){
            return Response::json([
                'message' => "User Already Exists"
            ], 409);
        }

        $query = UsersModels::create([
            "fullname" => $signupRequest->fullname,
            "username" => $signupRequest->username,
            "email" => $signupRequest->email,
            "userPassword" => password_hash($signupRequest->password, PASSWORD_DEFAULT)
        ]);

        //Check if query(insertion) failed
        if(!$query){
            return Response(null, 500);
        }

        return Response::json([
            'message' => $query
        ]);
    }
}
