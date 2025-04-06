<?php

namespace App\Http\Controllers;

use App\Models\Subject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::all();

        return response()->json([
            'subjects' => $subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create a new Subject
        $subject = Subject::create([
            'name' => $request->input('name'),
        ]);
        
        // Generate the URL for the subject
        $url = route('subject.show', ['id' => $subject->id]);
        
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_H,
        ]);
        
        // Define the file path in the public folder
        $filePath = public_path("qr/{$subject->id}.png");
        
        // Ensure the public/qr directory exists
        if (!file_exists(public_path('qr'))) {
            mkdir(public_path('qr'), 0755, true);
        }
        
        // Render and directly save to the file
        (new QRCode($options))->render($url, $filePath);

        // redirect to the subject's show page
        return redirect()->route('subject.show', ['id' => $subject->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = Subject::findOrFail($id);

        return view('subject.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subject = Subject::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        $subject->update($request->only('name'));

        return response()->json([
            'message' => 'Subject updated successfully.',
            'subject' => $subject,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);

        // Delete QR code file if exists
        $filePath = "qrcodes/{$subject->id}.png";
        Storage::disk('local')->delete($filePath);

        // Delete subject
        $subject->delete();

        return response()->json([
            'message' => 'Subject deleted successfully.',
        ]);
    }
}
