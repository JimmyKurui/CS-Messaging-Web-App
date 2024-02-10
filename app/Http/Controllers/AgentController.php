<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;

class AgentController extends Controller
{
    public function index(Request $request) {
        $agent = Agent::findOrFail(intval(json_decode($request->input('agent-id'))->id));
        return view('dashboard', ['agent' => $agent]);
    }

    public function messages() {
        return view('messages.index');
    }
}
