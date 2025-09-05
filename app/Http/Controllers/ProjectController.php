<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Program;
use App\Models\Facility;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // List all projects (with pagination)
    public function index()
    {
        // Use paginate instead of get()
        $projects = Project::with(['program', 'facility'])
            ->paginate(10) // Adjust number per page as needed
            ->withQueryString();

        $programs = Program::all();
        $facilities = Facility::all();

        return view('projects.index', compact('projects', 'programs', 'facilities'));
    }

    // Show the form to create a new project
    public function create()
    {
        $programs = Program::all();
        $facilities = Facility::all();
        return view('projects.create', compact('programs', 'facilities'));
    }

    // Store a new project
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'program_name' => 'required|string|exists:programs,name',
            'facility_name' => 'required|string|exists:facilities,name',
            'nature_of_project' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'innovation_focus' => 'nullable|string|max:255',
            'prototype_stage' => 'nullable|string|max:255',
            'testing_requirements' => 'nullable|string|max:255',
            'commercialization_plan' => 'nullable|string|max:255',
        ]);

        $program = Program::where('name', $validated['program_name'])->first();
        $facility = Facility::where('name', $validated['facility_name'])->first();

        Project::create([
            'title' => $validated['title'],
            'program_id' => $program->program_id,
            'facility_id' => $facility->facility_id,
            'nature_of_project' => $validated['nature_of_project'] ?? null,
            'description' => $validated['description'] ?? null,
            'innovation_focus' => $validated['innovation_focus'] ?? null,
            'prototype_stage' => $validated['prototype_stage'] ?? null,
            'testing_requirements' => $validated['testing_requirements'] ?? null,
            'commercialization_plan' => $validated['commercialization_plan'] ?? null,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    // Show a single project
    public function show($id)
    {
        $project = Project::with(['program', 'facility'])->findOrFail($id);
        return view('projects.show', compact('project'));
    }

    // Show the form to edit a project
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $programs = Program::all();
        $facilities = Facility::all();
        return view('projects.edit', compact('project', 'programs', 'facilities'));
    }

    // Update a project
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,program_id',
            'facility_id' => 'required|exists:facilities,facility_id',
            'nature_of_project' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'innovation_focus' => 'nullable|string|max:255',
            'prototype_stage' => 'nullable|string|max:255',
            'testing_requirements' => 'nullable|string|max:255',
            'commercialization_plan' => 'nullable|string|max:255',
        ]);

        $project = Project::findOrFail($id);
        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    // Delete a project
    public function destroy($id)
{
    $project = Project::findOrFail($id);
    
    // Delete all participants associated with this project first
    $project->participants()->delete();
    
    // Now delete the project
    $project->delete();

    return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
}
}
