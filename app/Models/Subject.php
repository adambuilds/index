<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubjectMeta;
use App\Models\SubjectLink;
use App\Models\Tag;

class Subject extends Model
{
    use HasUlids, HasFactory;

    protected $fillable = ['name', 'url', 'belongs_to_subject_id'];

    public function meta()
    {
        return $this->hasMany(SubjectMeta::class);
    }

    public function links()
    {
        return $this->hasMany(SubjectLink::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function parentSubject()
    {
        return $this->belongsTo(Subject::class, 'belongs_to_subject_id');
    }

    public function childSubjects()
    {
        return $this->hasMany(Subject::class, 'belongs_to_subject_id');
    }
}
