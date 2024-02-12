<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Message extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function ticket() :BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
