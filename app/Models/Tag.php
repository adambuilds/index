<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['name'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }
}
