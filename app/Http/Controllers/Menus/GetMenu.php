<?php

namespace App\Http\Controllers\Menus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class GetMenu extends Controller
{
    public function index(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response([
            'menus' => ($request->input("cat")
                ?
                Menu::where('category', $request->input('cat'))->get()
                : Menu::all()
            ),
            'token' => ($request->user()->value('role') == 'admin') ? $request->user()->first()->createToken('API_TOKEN', ['admin:roles'])->plainTextToken : $request->user()->first()->createToken('API_TOKEN')->plainTextToken
        ], 200);
    }
}
