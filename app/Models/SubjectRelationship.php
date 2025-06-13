<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectRelationship extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['subject_id', 'related_subject_id', 'type'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function related()
    {
        return $this->belongsTo(Subject::class, 'related_subject_id');
    }
}
