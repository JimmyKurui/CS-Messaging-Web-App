<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function assignedTicket(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'assigned', 'ticket_id', 'user_id');
    }

    public function ticket(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

}
