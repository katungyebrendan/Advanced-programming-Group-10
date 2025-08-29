<?php

namespace App\Services;

use InvalidArgumentException;

class ProjectService
{
    private $db;

    public function __construct()
    {
        $this->db = new JsonDB('projects'); // projects.json
    }

    public function getProjects(?int $facilityId = null, ?int $programId = null): array
    {
        $projects = collect($this->db->all());

        if ($facilityId) $projects = $projects->where('facility_id', $facilityId);
        if ($programId) $projects = $projects->where('program_id', $programId);

        return $projects->values()->toArray();
    }

    public function getProjectById(int $id): ?array
    {
        return $this->db->find($id);
    }

    public function createProject(array $data): array
    {
        $data['participants'] = $data['participants'] ?? [];
        $data['outcomes'] = $data['outcomes'] ?? [];
        if (empty($data['title'])) {
            throw new InvalidArgumentException('Project title is required.');
        }
        return $this->db->create($data);
    }

    public function updateProject(int $id, array $data): ?array
    {
        $project = $this->db->update($id, $data);
        if (!$project) throw new InvalidArgumentException("Project with ID {$id} not found.");
        return $project;
    }

    public function deleteProject(int $id): bool
    {
        $project = $this->db->find($id);
        if (!$project) throw new InvalidArgumentException("Project not found.");
        if (!empty($project['outcomes'])) {
            throw new InvalidArgumentException("Project cannot be deleted because it has outcomes.");
        }
        return $this->db->delete($id);
    }

    public function addOutcome(int $projectId, array $outcome): array
    {
        $projects = $this->db->all();
        foreach ($projects as &$project) {
            if ($project['project_id'] == $projectId) {
                $outcome['outcome_id'] = count($project['outcomes'] ?? []) + 1;
                $project['outcomes'][] = $outcome;
                $this->db->update($projectId, $project);
                return $outcome;
            }
        }
        throw new InvalidArgumentException("Project not found.");
    }

    public function listOutcomes(int $projectId): array
    {
        $project = $this->db->find($projectId);
        return $project['outcomes'] ?? [];
    }
}
