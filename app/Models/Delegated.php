<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Delegated extends Model
{
    use HasFactory;

    protected $table = 'delegated';

    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
