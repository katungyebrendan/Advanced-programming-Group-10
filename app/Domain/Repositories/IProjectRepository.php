<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\ProjectEntity;

interface IProjectRepository
{
    public function findById(int $id): ?ProjectEntity;
    public function existsByTitleInProgram(string $title, int $programId): bool;
    public function save(ProjectEntity $project): ProjectEntity;
    public function delete(int $id): void;
    public function hasTeamMembers(int $projectId): bool;
    public function hasOutcomes(int $projectId): bool;
    public function getAll(): array;
}