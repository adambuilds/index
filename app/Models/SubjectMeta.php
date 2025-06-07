<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectMeta extends Model
{
    use HasUlids, HasFactory;

    protected $table = 'subject_meta';

    protected $fillable = [
        'subject_id',
        'key',
        'value',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
