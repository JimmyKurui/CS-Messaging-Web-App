<?php

namespace App\Http\Controllers;

use App\Events\MessageBroadcast;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index() 
    {
        $messages = Message::all();
        return response()->json($messages);
    }
    public function get(Request $request, $user_id=null) 
    {
            $request->validate([
                'id' => 'required'
            ]);
            $message = Message::findOrFail($request->id);
            return response()->json($message);
    }
    
    public function getUserMessages($user_id) {
        $userMessages = Message::where('user_id', $user_id)->orderBy('timestamp_utc')->get();
        return $userMessages;
    }

    public function store(Request $request) 
    {
        $request->validate([
            'user_id' => 'required',
            'message' => 'required'
        ]);

        try {
            DB::transaction(function() use($request) {
                $message = Message::create([
                    'user_id' => $request->user_id,
                    'message'=> $request->message
                ]);
                return response()->json($message);
            });
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function broadcast(Request $request) 
    {
        // broadcast(new PusherBroadcaster($request->get('message')))->toOthers();
        if($request->get('broadcast')) {
            event(new MessageBroadcast($request->get('message'), $request->get('code')));
            return view('messages.broadcast', ['message' => $request->get('message'), 'code' => $request->get('code')]);
        } else {
            return view('messages.receive', ['message' => $request->get('message'), 'code' => $request->get('code')]);
        }
    }
}

