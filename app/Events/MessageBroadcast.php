<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // Message and sender's id/ any other identifier
    public $message;
    public $code;
    public $isAgent;
    public $ticketId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $code, $isAgent=false, $ticketId)
    {
        $this->message = $message;
        $this->code = $code;
        $this->isAgent = $isAgent;
        $this->ticketId = $ticketId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('public');
    }
    
    public function broadcastAs()
    {
        return 'chat';
    }

}
