<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Program;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

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
    public function store(CreateProjectRequest $request)
    {
        try {
            $validated = $request->validated();
            Project::create($validated);

            return redirect()->route('projects.index')->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the project: ' . $e->getMessage())->withInput();
        }
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
    public function update(UpdateProjectRequest $request, $id)
    {
        try {
            $project = Project::findOrFail($id);
            $validated = $request->validated();
            $project->update($validated);

            return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the project: ' . $e->getMessage())->withInput();
        }
    }

    // Delete a project
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            
            if (!$project->canBeDeleted()) {
                return redirect()->route('projects.index')
                               ->with('error', $project->getDeletionBlockReason());
            }
            
            // Detach participants (many-to-many relationship)
            $project->participants()->detach();
            
            // Now delete the project
            $project->delete();

            return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('projects.index')
                           ->with('error', 'An error occurred while deleting the project: ' . $e->getMessage());
        }
    }
}
