<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function agent(): HasOne
    {
        return $this->hasOne(Agent::class, 'id', 'agent_id');
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    public function assignedAgent(): BelongsToMany
    {
        return $this->belongsToMany(Agent::class, 'assigned', 'ticket_id', 'agent_id');
    }

    public static function update_header($ticket_id, $title, $priority, $note, $category_id, $status_id): bool
    {
        $ticket = Ticket::where('id', $ticket_id)->first();
        //verify if ticket exists
        if ($ticket) {
            //verify if nothing is null
            if ($title != null) {
                $ticket->title = $title;
            }
            if ($priority != null) {
                $ticket->priority_id = $priority;
            }
            if ($note != null) {
                $ticket->note = $note;
            }
            if ($category_id != null) {
                $ticket->category_id = $category_id;
            }
            if ($status_id != null) {
                $ticket->status_id = $status_id;
            }
            $ticket->save();

            return true;
        } else {
            return false;
        }
    }

    public function ticketMessages(): HasMany
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'ticket_id');
    }
}
