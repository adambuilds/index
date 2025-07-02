<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;

    /**
     * Get the thing that owns the property.
     */
    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }
}
