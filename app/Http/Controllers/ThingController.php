<?php

namespace App\Http\Controllers;

use App\Models\Thing;
use Illuminate\Http\Request;

class ThingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $things = Thing::all();
        return view('things.index', compact('things'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('things.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Validation rules
        ]);
        $thing = Thing::create($validated);
        return redirect()->route('things.show', $thing);
    }

    /**
     * Display the specified resource.
     */
    public function show(Thing $thing)
    {
        return view('things.show', compact('thing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Thing $thing)
    {
        return view('things.edit', compact('thing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Thing $thing)
    {
        $validated = $request->validate([
            // Validation rules
        ]);
        $thing->update($validated);
        return redirect()->route('things.show', $thing);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Thing $thing)
    {
        $thing->delete();
        return redirect()->route('things.index');
    }
}
