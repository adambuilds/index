<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectLink extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = [
        'subject_id',
        'title',
        'url',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
