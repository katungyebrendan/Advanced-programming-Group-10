<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Program;
use App\Models\Facility;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['program', 'facility']);

        // Filter by program if specified
        if ($request->filled('program_id')) {
            $query->byProgram($request->program_id);
        }

        // Filter by facility if specified
        if ($request->filled('facility_id')) {
            $query->byFacility($request->facility_id);
        }

        // Filter by nature if specified
        if ($request->filled('nature')) {
            $query->byNature($request->nature);
        }

        // Filter by stage if specified
        if ($request->filled('stage')) {
            $query->byStage($request->stage);
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $projects = $query->orderBy('title')->paginate(12);

        // Get filter options
        $programs = Program::orderBy('name')->get();
        $facilities = Facility::orderBy('name')->get();

        return view('projects.index', compact('projects', 'programs', 'facilities'));
    }

    public function create()
    {
        $programs = Program::orderBy('name')->get();
        $facilities = Facility::orderBy('name')->get();
        
        return view('projects.create', compact('programs', 'facilities'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'program_id' => 'required|exists:programs,program_id',
                'facility_id' => 'required|exists:facilities,facility_id',
                'title' => 'required|string|max:255',
                'nature_of_project' => 'required|in:' . implode(',', Project::NATURE_OPTIONS),
                'description' => 'required|string',
                'innovation_focus' => 'nullable|string|max:255',
                'prototype_stage' => 'required|in:' . implode(',', Project::PROTOTYPE_STAGES),
                'testing_requirements' => 'nullable|string',
                'commercialization_plan' => 'nullable|string',
            ]);

            $project = Project::create($data);

            return redirect()->route('projects.index')
                           ->with('success', 'Project created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while creating the project: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Project $project)
    {
        $project->load(['program', 'facility', 'participants', 'outcomes']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $programs = Program::orderBy('name')->get();
        $facilities = Facility::orderBy('name')->get();
        
        return view('projects.edit', compact('project', 'programs', 'facilities'));
    }

    public function update(Request $request, Project $project)
    {
        try {
            $data = $request->validate([
                'program_id' => 'required|exists:programs,program_id',
                'facility_id' => 'required|exists:facilities,facility_id',
                'title' => 'required|string|max:255',
                'nature_of_project' => 'required|in:' . implode(',', Project::NATURE_OPTIONS),
                'description' => 'required|string',
                'innovation_focus' => 'nullable|string|max:255',
                'prototype_stage' => 'required|in:' . implode(',', Project::PROTOTYPE_STAGES),
                'testing_requirements' => 'nullable|string',
                'commercialization_plan' => 'nullable|string',
            ]);

            $project->update($data);

            return redirect()->route('projects.index')
                           ->with('success', 'Project updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while updating the project: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Project $project)
    {
        try {
            if (!$project->canBeDeleted()) {
                return back()->with('error', $project->getDeletionBlockReason());
            }

            $project->delete();
            return redirect()->route('projects.index')
                           ->with('success', 'Project deleted successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the project: ' . $e->getMessage());
        }
    }

    // Additional methods for specific use cases
    public function byProgram(Program $program)
    {
        $projects = Project::byProgram($program->program_id)
                          ->with(['facility'])
                          ->orderBy('title')
                          ->paginate(12);
        
        return view('projects.by-program', compact('projects', 'program'));
    }

    public function byFacility(Facility $facility)
    {
        $projects = Project::byFacility($facility->facility_id)
                          ->with(['program'])
                          ->orderBy('title')
                          ->paginate(12);
        
        return view('projects.by-facility', compact('projects', 'facility'));
    }
}