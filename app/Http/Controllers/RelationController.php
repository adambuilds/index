<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use Illuminate\Http\Request;

class RelationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $relations = Relation::all();
        return view('relations.index', compact('relations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('relations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Validation rules
        ]);
        $relation = Relation::create($validated);
        return redirect()->route('relations.show', $relation);
    }

    /**
     * Display the specified resource.
     */
    public function show(Relation $relation)
    {
        return view('relations.show', compact('relation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Relation $relation)
    {
        return view('relations.edit', compact('relation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Relation $relation)
    {
        $validated = $request->validate([
            // Validation rules
        ]);
        $relation->update($validated);
        return redirect()->route('relations.show', $relation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Relation $relation)
    {
        $relation->delete();
        return redirect()->route('relations.index');
    }
}
