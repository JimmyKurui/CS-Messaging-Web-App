<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Helper\Functions;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\Cookie;

class AgentsController extends Controller
{
    public function index(Request $request) {
        $agent = Agent::findOrFail($request->id ?? $request->query('id'));
        // $agent = Agent::findOrFail(intval(json_decode($request->input('agent-id'))->id));
        $userConversations = Functions::getAllUserConversations(null, $agent->id);
        return response()->json($userConversations);

        // return view('dashboard', compact('agent', 'userConversations', 'statuses','priorities','categories'));
    }
}
