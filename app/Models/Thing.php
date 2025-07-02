<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thing extends Model
{
    /** @use HasFactory<\Database\Factories\ThingFactory> */
    use HasFactory;

    /**
     * Get the properties for the thing.
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get the outgoing relations for the thing.
     */
    public function outgoingRelations()
    {
        return $this->hasMany(Relation::class, 'from_thing_id');
    }

    /**
     * Get the incoming relations for the thing.
     */
    public function incomingRelations()
    {
        return $this->hasMany(Relation::class, 'to_thing_id');
    }
}
