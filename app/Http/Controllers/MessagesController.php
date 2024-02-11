<?php

namespace App\Http\Controllers;

use App\Events\MessageBroadcast;
use App\Helper\Functions;
use App\Models\Message;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    public function index(Request $request) 
    {
        $noTicketParam = 'noTicket';
        $userParam = 'user';

        if($request->has($noTicketParam)) {
            $messages = $this->getUnresolvedUsersMessages();
        }
        else {
            $messages = Message::all();
        }
        if($request->has($userParam)) {
            $messages = Functions::getAllUserConversations($request->query($userParam));
        }

        return response()->json($messages);
    }

    public function show(Request $request) 
    {
            $request->validate([
                'id' => 'required'
            ]);
            $message = Message::findOrFail($request->id);

            return response()->json($message);
    }
    
    public function store(Request $request) 
    {
        $request->validate([
            'message' => ['required', 'string'],
            'code' => ['required', 'integer'],
        ]);

        try {
            return DB::transaction(function() use($request) {
                $ticket = Ticket::find($request->ticket_id);
                // Check if ticket doesn't exist and create one
                if ($ticket == null || $ticket->end_time !== null) {
                    $messageFields = (Functions::checkPriorityAndCategory($request->message));
                    $request = new Request();
                    $request->merge([
                        'title' => 'New title',
                        'priority_id' => $messageFields['priority'],
                        'status_id' => 1,
                        'category_id' => $messageFields['category'],
                        'agent_id' => $request->user_id,
                        'user_id' => 4,
                        // Add other fields as needed
                    ]);
            
                    // Call the store method and pass the request instance
                    $response = $this->store($request);
                }
                $message = Message::create([
                    'user_id' => $request->user_id,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                    'body'=> $request->message,
                    'user_id' => $request->code,
                    'ticket_id' => $request->ticket_id ?? null,
                ]);
                Functions::broadcast($request);
                return response()->json($message);
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    

    public function getUnresolvedUsersMessages() {
        $usersMessages = Message::whereNull('ticket_id')->orderByDesc('timestamp')->get();
        return $usersMessages;
    }

    public function getUnresolvedUserMessages($userId) {
        $userMessages = Message::whereNull('ticket_id')->where('user_id', $userId)->orderByDesc('timestamp')->get();
        return $userMessages;
    }
}

