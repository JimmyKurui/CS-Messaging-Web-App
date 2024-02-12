<?php

namespace App\Http\Controllers;

use App\Helper\Functions;
use App\Models\User;
use App\Services\ChatHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UsersController extends Controller
{
    public function index(Request $request) {
        $user = User::findOrFail($request->id ?? $request->query('id') ?? $request->query('user'));
        $userConversations = Functions::getAllUserConversations($user->id);
        return response()->json($userConversations);
        
        // return view('dashboard', compact('user', 'messages'));
    }
}

