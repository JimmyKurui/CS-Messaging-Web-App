<?php

namespace App\Http\Controllers;

use App\Helper\Functions;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\throwException;

class TicketMessagesController extends Controller
{
    const STATUS_RESOLVED = 3;

    public function store(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string'],
            'code' => ['required', 'integer'],
            'ticketId' => ['required', 'integer'],
        ]);

        $ticket = Ticket::findOrFail($request->ticketId);
        // Disable new message if ticket is already resolved (3) 
        if(($ticket->statusId == self::STATUS_RESOLVED) && $ticket->end_time !== null) {
            throw new Exception('Ticket already resolved');
        }
        // Disable new ticket message when agent_ids are different - NO Assignee functionality
        if($ticket->agentId) {
            if($ticket->agentId != $request->code) {
                throw new Exception('Different agents cannot work on same user message(s)!');
            }
        }

        try {
            return DB::transaction(function () use ($request, $ticket) {
                $ticket = TicketMessage::create([
                    'body' => $request->message,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                    'agent_id' => (int)$request->code,
                    'ticket_id' => (int) $request->ticketId,
                ]);
                Functions::broadcast($request, $request->isAgent);
                return response()->json($ticket);

            });
        } catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


}
