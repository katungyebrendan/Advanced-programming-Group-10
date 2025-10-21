<?php

namespace App\Domain\Services;

use App\Domain\Entities\ProgramEntity;
use App\Domain\Repositories\IProgramRepository;

class ProgramDomainService
{
    public function __construct(
        private IProgramRepository $programRepository
    ) {}

    public function createProgram(ProgramEntity $program): array
    {
        // Validate entity
        $errors = $program->isValid();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Business Rule: Uniqueness (case-insensitive)
        if ($this->programRepository->existsCaseInsensitive($program->name)) {
            return ['success' => false, 'errors' => ['Program.Name already exists.']];
        }

        // Save program
        $savedProgram = $this->programRepository->save($program);
        
        return ['success' => true, 'program' => $savedProgram];
    }

    public function updateProgram(ProgramEntity $program): array
    {
        // Validate entity
        $errors = $program->isValid();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Business Rule: Uniqueness (case-insensitive, excluding current)
        $existing = $this->programRepository->findByName($program->name);
        if ($existing && $existing->id !== $program->id) {
            return ['success' => false, 'errors' => ['Program.Name already exists.']];
        }

        // Save program
        $savedProgram = $this->programRepository->save($program);
        
        return ['success' => true, 'program' => $savedProgram];
    }

    public function deleteProgram(int $programId): array
    {
        // Business Rule: Lifecycle Protection
        if ($this->programRepository->hasProjects($programId)) {
            return ['success' => false, 'errors' => ['Program has Projects; archive or reassign before delete.']];
        }

        $this->programRepository->delete($programId);
        
        return ['success' => true];
    }

    public function getAll(): array
    {
        return $this->programRepository->getAll();
    }

    public function findById(int $id): ?ProgramEntity
    {
        return $this->programRepository->findById($id);
    }
}