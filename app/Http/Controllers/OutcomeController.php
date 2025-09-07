<?php

namespace App\Http\Controllers;

use App\Models\Outcome;
use App\Models\Project;
use Illuminate\Http\Request;

class OutcomeController extends Controller
{
    // Display all outcomes for a specific project
    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        $outcomes = $project->outcomes()->latest()->paginate(9);
        return view('outcomes.index', compact('project', 'outcomes'));
    }

    // Show form to create a new outcome for a specific project
    public function create($projectId)
    {
        $selectedProject = Project::findOrFail($projectId);
        return view('outcomes.create', compact('selectedProject'));
    }

    // Store a new outcome for a specific project
    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'artifact_link' => 'nullable|string|max:255',
            'outcome_type' => 'nullable|string|max:255',
            'quality_certification' => 'nullable|string|max:255',
            'commercialization_status' => 'nullable|string|max:255',
        ]);

        // Create outcome associated with the project
        $project->outcomes()->create($validated);

        // Redirect to the project's outcomes index
        return redirect()->route('projects.outcomes.index', $project->project_id)
                         ->with('success', 'Outcome created successfully.');
    }

    // Show a single outcome
    public function show($id)
    {
        $outcome = Outcome::with('project')->findOrFail($id);
        return view('outcomes.show', compact('outcome'));
    }

    // Show form to edit an outcome
    public function edit($id)
    {
        $outcome = Outcome::with('project')->findOrFail($id);
        $projects = Project::all();
        return view('outcomes.edit', compact('outcome', 'projects'));
    }

    // Update an existing outcome
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,project_id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'artifact_link' => 'nullable|string|max:255',
            'outcome_type' => 'nullable|string|max:255',
            'quality_certification' => 'nullable|string|max:255',
            'commercialization_status' => 'nullable|string|max:255',
        ]);

        $outcome = Outcome::findOrFail($id);
        $outcome->update($validated);

        return redirect()->route('projects.outcomes.index', $outcome->project_id)
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
