<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SubjectMeta;
use Illuminate\Http\Request;

class SubjectMetaController extends Controller
{
    public function store(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'nullable|string',
        ]);

        $subject->meta()->create($data);

        return redirect()->route('subject.show', $subject->id);
    }

    public function destroy(Subject $subject, SubjectMeta $meta)
    {
        if ($meta->subject_id === $subject->id) {
            $meta->delete();
        }

        return redirect()->route('subject.show', $subject->id);
    }
}
