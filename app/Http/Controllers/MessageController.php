<?php

namespace App\Http\Controllers;

use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index() 
    {
        return view('messages.index');
    }
    
    public function broadcast(Request $request) 
    {
        broadcast(new PusherBroadcaster($request->get('message')))->toOthers();
        return view('messages.broadcast', ['message' => $request->get('message')]);
        
    }

    public function receive(Request $request) 
    {
        return view('messages.receive', ['message' => $request->get('message')]);
        
    }
}
