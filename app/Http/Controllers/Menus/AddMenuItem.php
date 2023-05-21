<?php

namespace App\Http\Controllers\Menus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddMenuItem extends Controller
{
    public function index(Request $request){
        $validator = Validator::make($request->all(),[
            'meal' => 'required | min:3',
            'price' => 'double | required',
            'category' => 'required | min:4',
            'stockAvailable' => 'required | integer',
            'image' => 'required | image'
        ]);

        if($validator->fails()){
            return response([
                'message' => 'Validation Error',
                'status' => 400,
                'error' => $validator->errors()
            ], 400);
        }

        if(!$request->user()->tokenCan('admin:roles')){
            return response([
                'message' => 'Unauthorized',
                'status' => 401
            ], 401);
        }

        return response([
            'message' => 'Access Granted',
            'status' => 200
        ], 200);
    }
}
