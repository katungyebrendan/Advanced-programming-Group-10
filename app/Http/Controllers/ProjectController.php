<?php

namespace App\Http\Controllers;

use App\Domain\Entities\ProjectEntity;
use App\Domain\Services\ProjectDomainService;
use App\Domain\Services\ProgramDomainService;
use App\Domain\Services\FacilityDomainService;
use Illuminate\Http\Request;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    private ProjectDomainService $projectService;
    private ProgramDomainService $programService;
    private FacilityDomainService $facilityService;

    public function __construct(
        ProjectDomainService $projectService,
        ProgramDomainService $programService,
        FacilityDomainService $facilityService
    ) {
        $this->projectService = $projectService;
        $this->programService = $programService;
        $this->facilityService = $facilityService;
    }

    // List all projects
    public function index(Request $request)
    {
        // Use Eloquent for listing so the view can use relations, helpers, and pagination
        $query = \App\Models\Project::query()->with(['program', 'facility']);

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }
        if ($request->filled('program_id')) {
            $query->byProgram($request->input('program_id'));
        }
        if ($request->filled('facility_id')) {
            $query->byFacility($request->input('facility_id'));
        }
        if ($request->filled('nature')) {
            $query->byNature($request->input('nature'));
        }

        $projects = $query->orderByDesc('project_id')->paginate(9)->withQueryString();

        // Domain services provide options for filters (can be entities or models; views use id fallbacks where needed)
        $programs = $this->programService->getAll();
        $facilities = $this->facilityService->getAll();

        return view('projects.index', compact('projects', 'programs', 'facilities'));
    }

    // Show the form to create a new project
    public function create()
    {
        $programs = $this->programService->getAll();
        $facilities = $this->facilityService->getAll();
        return view('projects.create', compact('programs', 'facilities'));
    }

    // Store a new project
    public function store(CreateProjectRequest $request)
    {
        $data = $request->validated();

        // Note: CreateProjectRequest merges program_id and facility_id after resolving by name,
        // but they are not part of validated() rules. Read them directly from the request input.
        $programId = (int) $request->input('program_id');
        $facilityId = (int) $request->input('facility_id');

        $entity = new ProjectEntity(
            id: null,
            programId: $programId,
            facilityId: $facilityId,
            title: $data['title'] ?? '',
            natureOfProject: $data['nature_of_project'] ?? null,
            description: $data['description'] ?? null,
            innovationFocus: $data['innovation_focus'] ?? null,
            prototypeStage: $data['prototype_stage'] ?? null,
            testingRequirements: $data['testing_requirements'] ?? null,
            commercializationPlan: $data['commercialization_plan'] ?? null,
            status: $data['status'] ?? 'Planning'
        );

        $result = $this->projectService->createProject($entity);

        if ($result['success']) {
            return redirect()->route('projects.index')->with('success', 'Project created successfully.');
        }

        return back()->withInput()->withErrors(['message' => implode('; ', $result['errors'] ?? ['Failed to create project'])]);
    }

    // Show a single project
    public function show($id)
    {
        $project = $this->projectService->findById($id);
        if (!$project) {
            return redirect()->route('projects.index')->withErrors(['message' => 'Project not found']);
        }
        return view('projects.show', compact('project'));
    }

    // Show the form to edit a project
    public function edit($id)
    {
        $project = $this->projectService->findById($id);
        if (!$project) {
            return redirect()->route('projects.index')->withErrors(['message' => 'Project not found']);
        }
        $programs = $this->programService->getAll();
        $facilities = $this->facilityService->getAll();
        return view('projects.edit', compact('project', 'programs', 'facilities'));
    }

    // Update a project
    public function update(UpdateProjectRequest $request, $id)
    {
        $data = $request->validated();

        $programId = (int) $request->input('program_id');
        $facilityId = (int) $request->input('facility_id');

        $entity = new ProjectEntity(
            id: (int) $id,
            programId: $programId,
            facilityId: $facilityId,
            title: $data['title'] ?? '',
            natureOfProject: $data['nature_of_project'] ?? null,
            description: $data['description'] ?? null,
            innovationFocus: $data['innovation_focus'] ?? null,
            prototypeStage: $data['prototype_stage'] ?? null,
            testingRequirements: $data['testing_requirements'] ?? null,
            commercializationPlan: $data['commercialization_plan'] ?? null,
            status: $data['status'] ?? 'Planning'
        );

        $result = $this->projectService->updateProject($entity);

        if ($result['success']) {
            return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
        }

        return back()->withInput()->withErrors(['message' => implode('; ', $result['errors'] ?? ['Failed to update project'])]);
    }

    // Delete a project
    public function destroy($id)
    {
        $result = $this->projectService->deleteProject($id);

        if ($result['success']) {
            return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
        }

        return back()->withErrors(['message' => implode('; ', $result['errors'] ?? ['Failed to delete project'])]);
    }
}
