<?php

namespace Database\Seeders;

use App\Helper\Functions;
use App\Models\Message;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create tickets for a user's messages
        $distinctUserIds = Message::distinct()->pluck('user_id');
        
        foreach ($distinctUserIds as $userId) {
            // Message sorter on a user's unticketed message(s) to determine rank 
            $userMessagesBody = Message::where('user_id', $userId)->pluck('body');
            $concatenatedBody = implode("\n", $userMessagesBody->toArray());
            $priorityAndCategory = Functions::checkPriorityAndCategory($concatenatedBody);
            // Auto ticket for unticketed messages
            $ticket = Ticket::create([
                'title' =>  'Start title '.Carbon::now()->format('Y-m-d H:i:s'),
                'category_id' =>  $priorityAndCategory['category'],
                'priority_id' =>  $priorityAndCategory['priority'],
                'status_id' =>  1,
                'user_id' => $userId,
                'start_time' => Carbon::now()->toDateTimeString(),
            ]);
            
            // Get all unticketed messages belonging to the current user and assign ticket
            Message::where('user_id', $userId)->update(['ticket_id' => $ticket->id]);
        }
    }
}
