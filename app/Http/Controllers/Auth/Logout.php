<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Logout extends Controller
{
    public function index(Request $request){
        try {
            $request->user()->tokens()->delete();
            return response([
                'message' => 'success',
                'status' => 200
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => 'You\'re already logged out',
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
