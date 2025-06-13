<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectRelationController extends Controller
{
    public function store(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'parent_id' => 'required|exists:subjects,id',
        ]);

        $subject->parents()->syncWithoutDetaching([$data['parent_id']]);

        return response()->json(['success' => true]);
    }

    public function destroy(Subject $subject, Subject $parent)
    {
        $subject->parents()->detach($parent->id);

        return response()->json(['success' => true]);
    }
}
