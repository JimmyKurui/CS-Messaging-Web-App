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

    const STATUS_CLOSED = 3;

    public function index(Request $request) 
    {
        $noTicketParam = 'noTicket';
        $userParam = 'user';

        
        if($request->has($noTicketParam)) {
            $messages = $this->getUnresolvedUsersMessages();
        }
        else if($request->has($userParam)) {
            $messages = Functions::getAllUserConversations($request->query($userParam));
        }
        else {
            $messages = Message::all();
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
                $ticket = Ticket::find($request->ticketId);
                // Check if ticket doesn't exist or resolved and create a new one
                if ($ticket == null || ($ticket->end_time !== null && $ticket->status_id == self::STATUS_CLOSED)) {

                    $messageFields = (Functions::checkPriorityAndCategory($request->message));
                    $newRequest = new Request();
                    $newRequest->merge([
                        'title' => 'New title - auto ticket',
                        'priorityId' => $messageFields['priority'],
                        'statusId' => 1,
                        'categoryId' => $messageFields['category'],
                        'userId' => (int)$request->code,
                    ]);
                    // Call the store method and pass the request instance
                    $ticketController = new TicketsController();
                    $ticket = $ticketController->store($newRequest);
                    $ticket = json_decode($ticket->content());
                }
                $message = Message::create([
                    'user_id' => (int)$request->user_id,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                    'body'=> $request->message,
                    'user_id' => (int) $request->code,
                    'ticket_id' => $ticket->id,
                ]);
                Functions::broadcast($request, false, $ticket->id);
                return response()->json($message);
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    

    public function getUnresolvedUsersMessages() {
        // $usersMessages = Message::whereHas('ticket', function($query) {
        //     $query->where('end_time', null)
        //             ->where('agent_id', null);
        //     })
        //     ->orWhere('ticket_id', null)
        //     ->with('ticket')
        //     ->orderBy('timestamp')->get();

        $usersMessages = Ticket::whereHas('messages', function($query) {
                    $query->where('ticket_id', null);
                })
                ->orWhere('end_time', null)
                ->where('agent_id', null)
                ->with('messages')
                ->orderBy('start_time')->get();
        return $usersMessages;
    }

    public function getUnresolvedUserMessages($userId) {
        $userMessages = Message::where('user_id', $userId)->has('ticket.end_time', '=', null)->orderBy('timestamp')->get();
        return $userMessages;
    }
}

