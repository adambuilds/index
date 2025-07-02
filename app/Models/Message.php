<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    /**
     * Get the author of the message.
     */
    public function author()
    {
        return $this->morphTo();
    }

    /**
     * Get the subject of the message.
     */
    public function subject()
    {
        return $this->morphTo();
    }
}
