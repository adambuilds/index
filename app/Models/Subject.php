<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectMeta;

class Subject extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['name', 'url'];

    public function meta()
    {
        return $this->hasMany(SubjectMeta::class);
    }
}
