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

class TicketMessagesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string'],
            'code' => ['required', 'integer'],
            'ticket_id' => ['required', 'integer'],
        ]);


        try {
            return DB::transaction(function () use ($request) {
                $ticket = Ticket::findOrFail($request->ticket_id);
                // Check if ticket is resolved via end time
                if ($ticket->end_time !== null) {
                    return new Exception('Ticket already resolved');
                }
                $ticket = TicketMessage::create([
                    'body' => $request->message,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
                    'agent_id' => $request->code,
                    'ticket_id' => $request->ticket_id,
                ]);
                Functions::broadcast($request, $request->isAgent);
                return response()->json($ticket);

            });
        } catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


}
