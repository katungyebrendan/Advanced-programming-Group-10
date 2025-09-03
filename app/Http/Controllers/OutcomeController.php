<?php

namespace App\Http\Controllers;

use App\Models\Outcome;
use App\Models\Project;
use Illuminate\Http\Request;

class OutcomeController extends Controller
{
    // List all outcomes for a given project
    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        $outcomes = Outcome::where('project_id', $projectId)->get();
        return view('outcomes.index', compact('project', 'outcomes'));
    }

    // Show form to create a new outcome for a project
    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('outcomes.create', compact('project'));
    }

    // Store a new outcome
    public function store(Request $request, $projectId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'artifact_link' => 'nullable|string|max:255',
            'outcome_type' => 'nullable|string|max:255',
            'quality_certification' => 'nullable|string|max:255',
        ]);

        $validated['project_id'] = $projectId;

        Outcome::create($validated);

        return redirect()->route('projects.outcomes.index', $projectId)
            ->with('success', 'Outcome created successfully.');
    }

    // Show a single outcome
    public function show($id)
    {
        $outcome = Outcome::findOrFail($id);
        return view('outcomes.show', compact('outcome'));
    }

    // Show form to edit an outcome
    public function edit($id)
    {
        $outcome = Outcome::findOrFail($id);
        $project = $outcome->project; // assumes Outcome has a 'project' relationship
        return view('outcomes.edit', compact('outcome', 'project'));
    }

    // Update an outcome
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'artifact_link' => 'nullable|string|max:255',
            'outcome_type' => 'nullable|string|max:255',
            'quality_certification' => 'nullable|string|max:255',
        ]);

        $outcome = Outcome::findOrFail($id);
        $outcome->update($validated);

        return redirect()->route('outcomes.show', $outcome->id)
            ->with('success', 'Outcome updated successfully.');
    }

    // Delete an outcome
    public function destroy($id)
    {
        $outcome = Outcome::findOrFail($id);
        $projectId = $outcome->project_id;
        $outcome->delete();

        return redirect()->route('projects.outcomes.index', $projectId)
            ->with('success', 'Outcome deleted successfully.');
    }
}
