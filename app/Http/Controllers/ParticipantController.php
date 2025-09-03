<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ParticipantController extends Controller
{
    // List all participants with pagination
    public function index(): View
    {
        $participants = Participant::paginate(10); // Paginate for better performance with many records
        return view('participants.index', compact('participants'));
    }

    // Show form to create a new participant
    public function create(): View
    {
        return view('participants.create', [
            'affiliations' => Participant::AFFILIATIONS,
            'specializations' => Participant::SPECIALIZATIONS,
            'institutions' => Participant::INSTITUTIONS
        ]);
    }

    // Store a new participant
    public function store(Request $request): RedirectResponse
{
    // Validate the incoming data from the form
    $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255|unique:participants,email',
        'affiliation' => 'required|in:' . implode(',', Participant::AFFILIATIONS),
        'specialization' => 'required|in:' . implode(',', Participant::SPECIALIZATIONS),
        'cross_skill_trained' => 'sometimes|boolean',
        'institution' => 'required|in:' . implode(',', Participant::INSTITUTIONS),
        'description' => 'nullable|string',
    ]);

    try {
        // Handle the checkbox input (if not checked, it won't be sent)
        $request->merge(['cross_skill_trained' => $request->has('cross_skill_trained')]);
        
        // Create a new participant record using the request data
        $participant = Participant::create($request->all());

        // Redirect to the participant show page with a success message
        return redirect()->route('participants.show', $participant)
                         ->with('success', 'Participant created successfully.');
    } catch (InvalidArgumentException $e) {
        // Redirect back with an error message for invalid arguments
        return back()->withInput()->withErrors(['message' => $e->getMessage()]);
    } catch (QueryException $e) {
        // Handle database-related errors
        return back()->withInput()->withErrors(['message' => 'Database error: Could not create participant.']);
    } catch (\Exception $e) {
        // Handle any other unexpected errors
        return back()->withInput()->withErrors(['message' => 'Failed to create participant. Please try again.']);
    }
}
    // Show a single participant using route model binding
    public function show(Participant $participant): View
    {
        return view('participants.show', compact('participant'));
    }

    // Show form to edit a participant using route model binding
    public function edit(Participant $participant): View
    {
        return view('participants.edit', [
            'participant' => $participant,
            'affiliations' => Participant::AFFILIATIONS,
            'specializations' => Participant::SPECIALIZATIONS,
            'institutions' => Participant::INSTITUTIONS
        ]);
    }

    // Update a participant using route model binding
    public function update(Request $request, Participant $participant): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:participants,email,' . $participant->id,
            'affiliation' => 'required|in:' . implode(',', Participant::AFFILIATIONS),
            'specialization' => 'required|in:' . implode(',', Participant::SPECIALIZATIONS),
            'cross_skill_trained' => 'sometimes|boolean',
            'institution' => 'required|in:' . implode(',', Participant::INSTITUTIONS),
            'description' => 'nullable|string',
        ]);

        $validated['cross_skill_trained'] = $request->boolean('cross_skill_trained');

        $participant->update($validated);

        return redirect()->route('participants.show', $participant)
                        ->with('success', 'Participant updated successfully.');
    }

    // Delete a participant using route model binding
    public function destroy(Participant $participant): RedirectResponse
    {
        if (!$participant->canBeDeleted()) {
            return redirect()->route('participants.index')
                            ->with('error', $participant->getDeletionBlockReason());
        }

        $participant->delete();

        return redirect()->route('participants.index')
                        ->with('success', 'Participant deleted successfully.');
    }

    // Show projects related to a participant using route model binding
    public function projects(Participant $participant): View
    {
        // Eager load projects for better performance
        $participant->load('projects');
        
        return view('participants.projects', compact('participant'));
    }
}