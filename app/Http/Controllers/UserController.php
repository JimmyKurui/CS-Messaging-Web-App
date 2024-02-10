<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request) {
        $user = User::findOrFail(intval(json_decode($request->input('user-id'))->id));
        return view('dashboard', ['user' => $user]);
    }

    public function messages() {
        return view('messages.index');
    }
}

