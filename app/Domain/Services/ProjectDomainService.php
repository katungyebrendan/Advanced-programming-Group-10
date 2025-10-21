<?php
namespace App\Domain\Services;

use App\Domain\Entities\ProjectEntity;
use App\Domain\Repositories\IProjectRepository;

class ProjectDomainService
{
    public function __construct(private IProjectRepository $projectRepository) {}

    public function createProject(ProjectEntity $project): array
    {
        $errors = $project->isValid();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        if ($this->projectRepository->existsByTitleInProgram($project->title, $project->programId)) {
            return ['success' => false, 'errors' => ['A project with this name already exists in this program']];
        }
        $saved = $this->projectRepository->save($project);
        return ['success' => true, 'project' => $saved];
    }

    public function updateProject(ProjectEntity $project): array
    {
        $errors = $project->isValid();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        $saved = $this->projectRepository->save($project);
        return ['success' => true, 'project' => $saved];
    }

    public function deleteProject(int $id): array
    {
        $this->projectRepository->delete($id);
        return ['success' => true];
    }

    public function getAll(): array
    {
        return $this->projectRepository->getAll();
    }

    public function findById(int $id): ?ProjectEntity
    {
        return $this->projectRepository->findById($id);
    }
}
