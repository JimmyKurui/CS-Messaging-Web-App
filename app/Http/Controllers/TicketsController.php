<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketsController extends Controller
{
    public function index(Request $request)
    {
        $idParam = 'user_id';
        if($request->has($idParam)) {
            $tickets = Ticket::with('ticketMessages')->where('user_id', $request->query($idParam));
        }
        else {
            $tickets = Ticket::with('ticketMessages');
        }
        return response()->json($tickets);
        // return view('tickets', compact('tickets'));
    }

    public function getTicketMessages($agentId) {
        $ticketMessages = Ticket::where('agent_id', $agentId)->orderByDesc('timestamp')->get();
        return $ticketMessages;
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'priority_id' => ['required', 'integer', 'between:1,3'],
            'status_id' => ['required', 'integer', 'between:1,3'],
            'agent_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
        ]);

        try {
            return DB::transaction(function () use ($request) {
                
                $ticket = Ticket::create([
                    'title' => $request->title ?? 'New title '.Carbon::now()->format('Y-m-d H:i:s'),
                    'description' => $request->description,
                    'labels' => $request->labels,
                    'category_id' => $request->category_id,
                    'priority_id' => $request->priority_id,
                    'status_id' => $request->status_id,
                    'agent_id' => $request->agent_id,
                    'user_id' => $request->user_id,
                    'start_time' => Carbon::now()->format('Y-m-d H:i:s'),
                    'end_time' => null
                ]);

                return response()->json($ticket);

            });
        } catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required','integer'],
            'title' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'datetime'],
            'priority_id' => ['required', 'integer', 'between:1,3'],
            'status_id' => ['required', 'integer', 'between:1,3'],
            'agent_id' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $ticket = new Ticket();
                $ticket->fill([
                    'title' => $request->title,
                    'description' => $request->description,
                    'labels' => $request->labels,
                    'category_id' => $request->category_id,
                    'priority_id' => $request->priority_id,
                    'status_id' => $request->status_id,
                    'agent_id' => $request->agent_id,
                    'user_id' => $request->user_id,
                    'start_time' => Carbon::now(),
                    'end_time' => null
                ]);

                return response()->json($ticket);

            });
        } catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
