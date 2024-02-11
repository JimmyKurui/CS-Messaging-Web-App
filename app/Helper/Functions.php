<?php

namespace App\Helper;

use App\Events\MessageBroadcast;
use App\Models\Message;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Exception;
use Hamcrest\Type\IsBoolean;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Functions
{
    public static function getAllUserConversations($userId = null, $agentId = null)
    {
        try {
            // Load user's own data or last user agent worked on
            $userId = $userId ? $userId
                : Ticket::where('end_time', null)
                ->where('agent_id', $agentId)
                ->orderByDesc('id')
                ->first()?->user_id;
            // If no last user load first unfinished user issue
            $userId = $userId ? $userId : Message::where('ticket_id', null)->orderBy('timestamp')->first()->user_id;

            // ----- Join -----
            // $userConversations = Message::leftJoin('ticket_messages', 'messages.ticket_id', '=', 'ticket_messages.ticket_id')

            //     ->where('user_id', $userId)
            //     ->orderByDesc('messages.timestamp')
            //     ->orderByDesc('ticket_messages.timestamp')
            //     ->select('messages.*', 'ticket_messages.agent_id', 'ticket_messages.timestamp as ticket_timestamp', 'ticket_messages.body as ticket_body')
            //     ->get();

            // ----- Union Of Messages and Ticket_Messages only -------
            // $userMessages = Message::select('id','user_id', 'timestamp', 'body', 'ticket_id')
            //     ->selectRaw('false as isAgent')
            //     ->where('user_id', '=', $userId);

            // $ticketIds = $userMessages->pluck('ticket_id');

            // $relatedTicketMessages = TicketMessage::select('id','agent_id', 'timestamp', 'body', 'ticket_id')
            //     ->selectRaw('true as isAgent')
            //     ->whereIn('ticket_id', $ticketIds);

            // $userConversations = $userMessages->union($relatedTicketMessages)
            //     ->orderBy('timestamp')
            //     ->with('ticket')
            //     ->get();

            // Ticket with all message types then join
            $ticketsWithBothMessages = Ticket::with('messages', 'ticketMessages')->where('user_id', $userId)->orderBy('id')->get();

            foreach ($ticketsWithBothMessages as $ticket) {
                $messages = $ticket->messages()->select('id', 'timestamp', 'body', 'user_id','ticket_id')
                    ->selectRaw("false as isAgent");
                    // ->get();

                $ticketMessages = $ticket->ticketMessages()->select('id', 'timestamp', 'body', 'agent_id','ticket_id')
                    ->selectRaw("true as isAgent");
                    // ->get();

                $combinedMessages = $messages->union($ticketMessages)->orderBy('timestamp')->get();
                $ticket->setAttribute('combinedMessages', $combinedMessages);
                unset($ticket->messages);
                unset($ticket->ticketMessages);
            }        

            $userConversations = $ticketsWithBothMessages;
            return $userConversations;

        } catch (Exception $th) {
            throw $th;
        }
    }


    public static function getAllUnresolvedConversations()
    {
        try {
            // Load user's own data or last user agent worked on if agent
            $chatHistory = Message::leftJoin('ticket_messages', 'messages.ticket_id', '=', 'ticket_messages.ticket_id')
                ->with('tickets')
                ->where('tickets.end_time', null)
                ->orderByDesc('messages.timestamp')
                ->orderByDesc('ticket_messages.timestamp')
                ->select('messages.*', 'ticket_messages.agent_id', 'ticket_messages.timestamp as ticket_timestamp', 'ticket_messages.body as ticket_body')
                ->get();
            return $chatHistory;
        } catch (Exception $th) {
            throw $th;
        }
    }

    public static function broadcast(Request $request, $isAgent = false)
    {
        // broadcast(new PusherBroadcaster($request->get('message')))->toOthers();
        if ($request->broadcast) {
            event(new MessageBroadcast($request->message, $request->code, $isAgent, $request->ticket_id));
        }
    }

    public static function checkPriorityAndCategory(string $text): array
    {
        $priority = 1;
        $category = 1;
        // Check for priority value
        if (preg_match('/\b(urgent|emergency|loan|delay|suspen.*|access|application|system|.*batch.* )\b/i', $text)) {
            $priority = 3;
        } elseif (preg_match('/\b(high|important|payment.*|clear.*|m-pesa|.*fault.*)\b/i', $text)) {
            $priority = 2;
        } elseif (preg_match('/\b(.*quest.*|thanks|inquiry|reject.*|sorry|remind.*|promise.*|penalty|.*will pay.*)\b/i', $text)) {
            $priority = 1;
        }

        // 1 - General 2- Finance 3- Admin
        // Check for category value
        if (preg_match('/\b(loan|credit|finance|system)\b/i', $text)) {
            $category = 2;
        } elseif (preg_match('/\b(inquiry|question|help|transactions|batch)\b/i', $text)) {
            $category = 1;
        } elseif (preg_match('/\b(admin|account|security|application)\b/i', $text)) {
            $category = 3;
        }

        return ['priority' => $priority, 'category' => $category];
    }
}
