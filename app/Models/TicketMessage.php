<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TicketMessage extends Model
{
    use HasFactory;
    protected $guarded= [];
    public $timestamps = false;

    // public function agent(): HasOne
    // {
    //     return $this->hasOne(Agent::class, 'id', 'agent_id');
    // }

    public function assignedAgents(): BelongsToMany
    {
        return $this->belongsToMany(Agent::class, 'id', 'agent_id');
    }

    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class, 'id', 'ticket_id');
    }
}
