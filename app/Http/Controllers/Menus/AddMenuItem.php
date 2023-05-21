<?php

namespace App\Http\Controllers\Menus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu;

class AddMenuItem extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'meal' => 'required | min:3',
            'price' => 'required',
            'category' => 'required | min:4',
            'stockAvailable' => 'required | integer',
            'image' => 'required | image'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Validation Error',
                'status' => 400,
                'error' => $validator->errors()
            ], 400);
        }

        if (!$request->user()->tokenCan('admin:roles')) {
            return response([
                'message' => 'Unauthorized',
                'status' => 401
            ], 401);
        }

        $user->currentAccessToken()->delete();

        $image = $request->file('image');
        if (!$image->isValid()) {
            return response([
                'message' => 'Invaid Image File',
                'status' => 409,
                'token' => (($user->value('role') == 'admin') ?
                    $user->first()->createToken('API_TOKEN', ['admin:roles'])->plainTextToken
                    :
                    $user->first()->createToken('API_TOKEN', ['user:role'])->plainTextToken
                )
            ], 409);
        }

        if (!$image->extension() == "jpg" || !$image->extension() == "png" || !$image->extension() == "webp" || !$image->extension() == "svg") {
            return response([
                'message' => 'Invalid format',
                'status' => 409,
                'token' => (($user->value('role') == 'admin') ?
                    $user->first()->createToken('API_TOKEN', ['admin:roles'])->plainTextToken
                    :
                    $user->first()->createToken('API_TOKEN', ['user:role'])->plainTextToken
                )
            ], 409);
        }

        $path = "";
        try {
            global $path;
            $path = $image->store('images/meals');
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error storing image',
                'status' => 500,
                'error' => $th->getMessage(),
                'token' => (($user->value('role') == 'admin') ?
                    $user->first()->createToken('API_TOKEN', ['admin:roles'])->plainTextToken
                    :
                    $user->first()->createToken('API_TOKEN', ['user:role'])->plainTextToken
                )
            ], 500);
        }

        try {
            Menu::create([
                'meal' => $request->meal,
                'price' => $request->price,
                'category' => $request->category,
                'stockAvailable' => $request->stockAvailable,
                'image' => url($path)
            ]);

            return response([
                'message' => 'success',
                'status' => 200,
                'token' => (($user->value('role') == 'admin') ?
                    $user->first()->createToken('API_TOKEN', ['admin:roles'])->plainTextToken
                    :
                    $user->first()->createToken('API_TOKEN', ['user:role'])->plainTextToken
                )
            ], 200);
        } catch (\Throwable $th) {
            if(!unlink(public_path($path))){
                return response([
                    'message' => 'Image not deleted, please contact server side devs',
                    'status' => 302
                ], 302);
            }
            return response([
                'message' => 'Error saving item to database',
                'status' => 500,
                'error' => $th->getMessage(),
                'token' => (($user->value('role') == 'admin') ?
                    $user->first()->createToken('API_TOKEN', ['admin:roles'])->plainTextToken
                    :
                    $user->first()->createToken('API_TOKEN', ['user:role'])->plainTextToken
                )
            ], 500);
        }
    }
}
