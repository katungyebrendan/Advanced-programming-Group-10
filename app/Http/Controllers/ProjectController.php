<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Program;
use App\Models\Facility;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // List all projects
    public function index()
    {
        $projects = Project::with(['program', 'facility'])->get();
        return view('projects.index', compact('projects'));
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
            'program_id' => 'required|exists:programs,id',
            'facility_id' => 'required|exists:facilities,id',
            'nature_of_project' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'innovation_focus' => 'nullable|string|max:255',
            'prototype_stage' => 'nullable|string|max:255',
            'testing_requirements' => 'nullable|string|max:255',
            'commercialization_plan' => 'nullable|string|max:255',
        ]);

        Project::create($validated);

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
            'program_id' => 'required|exists:programs,id',
            'facility_id' => 'required|exists:facilities,id',
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
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
