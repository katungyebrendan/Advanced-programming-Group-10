<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class ProjectController extends Controller
{
    private ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    // 🔹 List all projects (optionally filtered by facility or program)
    public function index(Request $request): JsonResponse
    {
        $facilityId = $request->query('facility_id');
        $programId = $request->query('program_id');

        $projects = $this->projectService->getProjects($facilityId, $programId);

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    // 🔹 List projects under a facility
    public function byFacility(int $facilityId): JsonResponse
    {
        $projects = $this->projectService->getProjects($facilityId, null);

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    // 🔹 List projects under a program
    public function byProgram(int $programId): JsonResponse
    {
        $projects = $this->projectService->getProjects(null, $programId);

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    // 🔹 View a specific project
    public function show(int $id): JsonResponse
    {
        $project = $this->projectService->getProjectById($id);

        if (!$project) {
            return response()->json(['success' => false, 'message' => 'Project not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $project]);
    }

    // 🔹 Create a new project
    public function store(Request $request): JsonResponse
    {
        try {
            $project = $this->projectService->createProject($request->all());
            return response()->json(['success' => true, 'data' => $project], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // 🔹 Update a project
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $project = $this->projectService->updateProject($id, $request->all());
            return response()->json(['success' => true, 'data' => $project]);
        } catch (InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // 🔹 Delete a project
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->projectService->deleteProject($id);
            return response()->json(['success' => true, 'message' => 'Project deleted']);
        } catch (InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // 🔹 List outcomes for a project
    public function listOutcomes(int $id): JsonResponse
    {
        $outcomes = $this->projectService->listOutcomes($id);

        return response()->json(['success' => true, 'data' => $outcomes]);
    }

    // 🔹 Add an outcome to a project
    public function addOutcome(Request $request, int $id): JsonResponse
    {
        try {
            $outcome = $this->projectService->addOutcome($id, $request->all());
            return response()->json(['success' => true, 'data' => $outcome], 201);
        } catch (InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
