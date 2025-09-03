<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Project;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    // List all participants
    public function index()
    {
        $participants = Participant::all();
        return view('participants.index', compact('participants'));
    }

    // Show form to create a new participant
    public function create()
    {
        return view('participants.create');
    }

    // Store a new participant
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Participant::create($validated);

        return redirect()->route('participants.index')->with('success', 'Participant created successfully.');
    }

    // Show a single participant
    public function show($id)
    {
        $participant = Participant::findOrFail($id);
        return view('participants.show', compact('participant'));
    }

    // Show form to edit a participant
    public function edit($id)
    {
        $participant = Participant::findOrFail($id);
        return view('participants.edit', compact('participant'));
    }

    // Update a participant
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $participant = Participant::findOrFail($id);
        $participant->update($validated);

        return redirect()->route('participants.show', $participant->id)->with('success', 'Participant updated successfully.');
    }

    // Delete a participant
    public function destroy($id)
    {
        $participant = Participant::findOrFail($id);
        $participant->delete();

        return redirect()->route('participants.index')->with('success', 'Participant deleted successfully.');
    }

    // Show projects related to a participant
    public function projects($participantId)
    {
        $participant = Participant::findOrFail($participantId);
        $projects = $participant->projects; // assumes Participant has a 'projects' relationship

        return view('participants.projects', compact('participant', 'projects'));
    }
}
