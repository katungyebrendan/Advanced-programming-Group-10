<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;

class ParticipantController extends Controller
{
    public function create()
    {
        $projects = Project::all();
        $affiliations = Participant::AFFILIATIONS;
        $specializations = Participant::SPECIALIZATIONS;
        $institutions = Participant::INSTITUTIONS;
        
        return view('participants.create', compact('projects', 'affiliations', 'specializations', 'institutions'));
    }
public function index()
{
    // Change from get() to paginate()
    $participants = Participant::withCount('projects')->paginate(10); // 10 items per page
    
    return view('participants.index', compact('participants'));
}

public function show(Participant $participant)
{
    $participant->load('projects');
    return view('participants.show', compact('participant'));
}
    public function store(CreateParticipantRequest $request)
    {
        // Additional validation for project assignments
        $request->validate([
            'projects' => 'sometimes|array',
            'projects.*' => 'exists:projects,project_id',
            'roles' => 'sometimes|array',
            'skill_roles' => 'sometimes|array',
            'roles.*' => 'nullable|string|max:255',
            'skill_roles.*' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            // Create the participant using validated data
            $validated = $request->validated();
            $participant = Participant::create($validated);

            // Attach projects with pivot data if provided
            if ($request->has('projects')) {
                $projectsData = [];
                foreach ($request->projects as $projectId) {
                    $projectsData[$projectId] = [
                        'role_on_project' => $request->roles[$projectId] ?? null,
                        'skill_role' => $request->skill_roles[$projectId] ?? null
                    ];
                }
                $participant->projects()->attach($projectsData);
            }

            DB::commit();

            return redirect()->route('participants.index')
                ->with('success', 'Participant created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create participant: ' . $e->getMessage());
        }
    }

    public function edit(Participant $participant)
    {
        $projects = Project::all();
        $affiliations = Participant::AFFILIATIONS;
        $specializations = Participant::SPECIALIZATIONS;
        $institutions = Participant::INSTITUTIONS;
        
        // Load participant with projects to get current assignments
        $participant->load('projects');

        return view('participants.edit', compact('participant', 'projects', 'affiliations', 'specializations', 'institutions'));
    }

    public function update(UpdateParticipantRequest $request, Participant $participant)
    {
        // Additional validation for project assignments
        $request->validate([
            'projects' => 'sometimes|array',
            'projects.*' => 'exists:projects,project_id',
            'roles' => 'sometimes|array',
            'skill_roles' => 'sometimes|array',
            'roles.*' => 'nullable|string|max:255',
            'skill_roles.*' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            // Update participant details using validated data
            $validated = $request->validated();
            $participant->update($validated);

            // Sync projects with pivot data
            if ($request->has('projects')) {
                $projectsData = [];
                foreach ($request->projects as $projectId) {
                    $projectsData[$projectId] = [
                        'role_on_project' => $request->roles[$projectId] ?? null,
                        'skill_role' => $request->skill_roles[$projectId] ?? null
                    ];
                }
                $participant->projects()->sync($projectsData);
            } else {
                // If no projects selected, detach all
                $participant->projects()->detach();
            }

            DB::commit();

            return redirect()->route('participants.index')
                ->with('success', 'Participant updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update participant: ' . $e->getMessage());
        }
    }

    public function destroy(Participant $participant)
    {
        if (!$participant->canBeDeleted()) {
            return redirect()->route('participants.index')
                ->with('error', $participant->getDeletionBlockReason());
        }

        DB::beginTransaction();

        try {
            // Detach all projects first
            $participant->projects()->detach();
            
            // Then delete the participant
            $participant->delete();

            DB::commit();

            return redirect()->route('participants.index')
                ->with('success', 'Participant deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('participants.index')
                ->with('error', 'Failed to delete participant: ' . $e->getMessage());
        }
    }

    // Additional method to manage project assignments
    public function manageProjects(Participant $participant)
    {
        $projects = Project::all();
        $participant->load('projects');
        
        return view('participants.manage-projects', compact('participant', 'projects'));
    }

    public function updateProjects(Request $request, Participant $participant)
    {
        $request->validate([
            'projects' => 'sometimes|array',
            'projects.*' => 'exists:projects,project_id',
            'roles' => 'sometimes|array',
            'skill_roles' => 'sometimes|array',
            'roles.*' => 'nullable|string|max:255',
            'skill_roles.*' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            if ($request->has('projects')) {
                $projectsData = [];
                foreach ($request->projects as $projectId) {
                    $projectsData[$projectId] = [
                        'role_on_project' => $request->roles[$projectId] ?? null,
                        'skill_role' => $request->skill_roles[$projectId] ?? null
                    ];
                }
                $participant->projects()->sync($projectsData);
            } else {
                $participant->projects()->detach();
            }

            DB::commit();

            return redirect()->route('participants.show', $participant)
                ->with('success', 'Project assignments updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update project assignments: ' . $e->getMessage());
        }
    }
}