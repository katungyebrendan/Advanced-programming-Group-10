<?php

namespace App\Http\Controllers;

use App\Models\Participant;
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
            'full_name'          => 'required|string|max:255',
            'email'              => 'required|email|max:255|unique:participants,email',
            'affiliation'        => 'nullable|in:CS,SE,Engineering,Other',
            'specialization'     => 'nullable|in:Software,Hardware,Business',
            'cross_skill_trained'=> 'boolean',
            'institution'        => 'nullable|in:SCIT,CEDAT,UniPod,UIRI,Lwera',
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
            'full_name'          => 'required|string|max:255',
            'email'              => 'required|email|max:255|unique:participants,email,' . $id . ',participant_id',
            'affiliation'        => 'nullable|in:CS,SE,Engineering,Other',
            'specialization'     => 'nullable|in:Software,Hardware,Business',
            'cross_skill_trained'=> 'boolean',
            'institution'        => 'nullable|in:SCIT,CEDAT,UniPod,UIRI,Lwera',
        ]);

        $participant = Participant::findOrFail($id);
        $participant->update($validated);

        return redirect()->route('participants.show', $participant->participant_id)
                         ->with('success', 'Participant updated successfully.');
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
        $projects = $participant->projects; // uses belongsToMany from the model

        return view('participants.projects', compact('participant', 'projects'));
    }
}
