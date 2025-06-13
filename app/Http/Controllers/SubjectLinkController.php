<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SubjectLink;
use Illuminate\Http\Request;

class SubjectLinkController extends Controller
{
    public function store(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        $subject->links()->create($data);

        return redirect()->route('subject.show', $subject->id);
    }

    public function destroy(Subject $subject, SubjectLink $link)
    {
        if ($link->subject_id === $subject->id) {
            $link->delete();
        }

        return redirect()->route('subject.show', $subject->id);
    }
}
