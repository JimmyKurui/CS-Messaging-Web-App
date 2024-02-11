<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Message extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function ticket() :HasOne
    {
        return $this->hasOne(TicketMessage::class, 'ticket_id');
    }
}
