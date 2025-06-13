<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectRelationshipController extends Controller
{
    public function store(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'parent_id' => 'required|exists:subjects,id',
        ]);

        $subject->parents()->syncWithoutDetaching([$data['parent_id'] => ['type' => 'belongs_to']]);

        return redirect()->route('subject.show', $subject->id);
    }

    public function destroy(Subject $subject, Subject $parent)
    {
        $subject->parents()->detach($parent->id);

        return redirect()->route('subject.show', $subject->id);
    }
}
