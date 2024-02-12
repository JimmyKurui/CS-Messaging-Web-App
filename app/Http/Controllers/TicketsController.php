<?php

namespace App\Http\Controllers;

use App\Helper\Functions;
use App\Models\Category;
use App\Models\Message;
use App\Models\Ticket;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketsController extends Controller
{
    const STATUS_RESOLVED = 3;

    public function index(Request $request)
    {
        $userIdParam = 'user_id';
        $agentIdParam = 'agent';
        if($request->has($userIdParam)) {
            $tickets = Ticket::with('messages')->where('user_id', $request->query($userIdParam));
        }
        else if($request->has($agentIdParam)) {
            $tickets = Ticket:://where('end_time', null)
            where('agent_id', $request->query($agentIdParam))
            ->orderBy('id')->get();
        }
        else {
            $tickets = Ticket::all();
        }
        return response()->json($tickets);
        // return view('tickets', compact('tickets'));
    }

    public function show($id) {
        $ticket = Ticket::findOrFail($id);
        return $ticket;
    }

    public function store(Request $request)
    {
        $priorityAndCategory = null;

        if($request->has('autoTicket')) {
            $priorityAndCategory = Functions::checkPriorityAndCategory($request->message);
        }
        else {
            $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'priorityId' => ['required', 'integer', 'between:1,3'],
                'statusId' => ['required', 'integer', 'between:1,3'],
                // 'agentId' => ['required', 'integer'],
                'userId' => ['required', 'integer'],
            ]);
        }
        $priorityAndCategory = $priorityAndCategory ? $priorityAndCategory : null;
        try {
            return DB::transaction(function () use ($request, $priorityAndCategory) {
                
                $ticket = Ticket::create([
                    'title' => $request->title ?? 'New title '.Carbon::now()->format('Y-m-d H:i:s'),
                    'description' => $request->description,
                    'labels' => $request->labels,
                    'category_id' => $request->categoryId ?? $priorityAndCategory['category'],
                    'priority_id' => $request->priorityId ?? $priorityAndCategory['priority'],
                    'status_id' => $request->statusId ?? 1,
                    'agent_id' => $request->agentId,
                    'user_id' => $request->userId,
                    'start_time' => Carbon::now()->format('Y-m-d H:i:s'),
                    'end_time' => null
                ]);
                $this->closePreviousUnresolvedTickets($ticket, $request->userId);
                Functions::broadcastTicketSelection();
                return response()->json($ticket);

            });
        } catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        if($request->isMethod('PATCH')) {
            $request->validate([
                'agentId' => ['required', 'integer'],
                'statusId' => ['required', 'integer'],
            ]);
        } else {
            $request->validate([
                'title' => ['required', 'string'],
                'startTime' => ['required'],
                'priorityId' => ['required', 'integer', 'between:1,3'],
                'statusId' => ['required', 'integer', 'between:1,3'],
                'agentId' => ['required', 'integer'],
                'userId' => ['required', 'integer'],
            ]);
        }
        $ticket = Ticket::findOrFail($id);
        if(($ticket->status_id == self::STATUS_RESOLVED) && $ticket->end_time !== null) {
            throw new Exception('Ticket already resolved');
        }
        // Disable ticket update when agent_ids are different - NO Assignee functionality
        if($ticket->agent_id) {
            if($ticket->agent_id != $request->agentId) {
                throw new Exception('Ticket already assigned!');
            }
        }

            
        try {
            return DB::transaction(function () use ($request, $ticket) {
                if($request->isMethod('PATCH')) {
                    $ticket->update([
                        'status_id' => $request->statusId,
                        'agent_id' => $request->agentId,
                    ]);
                    Functions::broadcastTicketSelection();
                } else {
                    $ticket->fill([
                        'title' => $request->title,
                        'description' => $request->description,
                        'labels' => $request->labels,
                        'category_id' => $request->categoryId,
                        'priority_id' => $request->priorityId,
                        'status_id' => $request->statusId,
                        'agent_id' => $request->agentId,
                        'user_id' => $request->userId,
                        'start_time' => $request->startTime,
                        'end_time' => $request->endTime,
                    ]);
                }
                $ticket->save();

                $this->closePreviousUnresolvedTickets($ticket, $request->userId);

                return response()->json($ticket);

            });
        } catch (QueryException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function closePreviousUnresolvedTickets($currentTicket = null, $userId) {
        if($currentTicket == null) {
            // Latest open
            $currentTicket = Ticket::where('user_id', $userId)
                ->where('end_time',  null)
                ->orderByDesc('id')->first();
        }
        Ticket::where('user_id', $userId)
            ->where('id', '!=', $currentTicket->id)
            ->where('end_time',  null)
            ->update([
                'description' => 'Closure overwrite',
                'end_time' => Carbon::now()->format('Y-m-d H:i:s'),
                'status_id' => self::STATUS_RESOLVED
            ]);
    }
}
