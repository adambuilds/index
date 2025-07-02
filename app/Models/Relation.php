<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    /** @use HasFactory<\Database\Factories\RelationFactory> */
    use HasFactory;

    /**
     * Get the origin thing of the relation.
     */
    public function fromThing()
    {
        return $this->belongsTo(Thing::class, 'from_thing_id');
    }

    /**
     * Get the target thing of the relation.
     */
    public function toThing()
    {
        return $this->belongsTo(Thing::class, 'to_thing_id');
    }
}
