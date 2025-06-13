<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Tag;
use Illuminate\Http\Request;

class SubjectTagController extends Controller
{
    public function store(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = Tag::firstOrCreate(['name' => $data['name']]);
        $subject->tags()->syncWithoutDetaching([$tag->id]);

        return redirect()->route('subject.show', $subject->id);
    }

    public function destroy(Subject $subject, Tag $tag)
    {
        $subject->tags()->detach($tag->id);

        return redirect()->route('subject.show', $subject->id);
    }
}
