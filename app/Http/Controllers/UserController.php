<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    // Initial message loader through controller access
    protected $messageController;

    public function __construct(MessageController $messageController) {
        $this->messageController = $messageController;
    }

    public function index(Request $request) {
        $user = User::findOrFail(intval(json_decode($request->input('user-id'))->id));
        $messages = $this->messageController->getUserMessages($user->id);
        return view('dashboard', compact('user', 'messages'));
    }

    public function messages() {
        return view('messages.index');
    }
}

