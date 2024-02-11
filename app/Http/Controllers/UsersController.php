<?php

namespace App\Http\Controllers;

use App\Helper\Functions;
use App\Models\User;
use App\Services\ChatHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UsersController extends Controller
{

    // Initial message loader through controller access
    protected $messageController;

    public function __construct(MessagesController $messageController) {
        $this->messageController = $messageController;
    }

    public function index(Request $request) {
        $user = User::findOrFail($request->id ?? $request->query('id'));
        // $user = User::findOrFail(intval(json_decode($request->input('user-id'))->id));
        $userConversations = Functions::getAllUserConversations($user->id);
        // if ($request->hasCookie('agent_id')) {
        //     setcookie('agent_id', null);
        // } else {
        //     setcookie('user_id', $user->id, time() + 180);
        // }
        $noTicketParam = 'noTicket';
        if($request->is('api/support')) {
            if($request->has($noTicketParam)) {
                // $userConversations =Functions::getNullUserMessages();
            }
            return response()->json($userConversations);
        }
        return view('dashboard', compact('user', 'messages'));
    }
    
    public function getUserConversations(Request $request) {
        if($request->has('user')) {
            $messages = Functions::getAllUserConversations($request->query('user'));
        }
        else {
            $messages = Functions::getAllUserConversations($request->id);
        }
        return response()->json($messages); 
    }
}

