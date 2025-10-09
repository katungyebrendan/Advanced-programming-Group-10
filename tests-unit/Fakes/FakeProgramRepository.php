<?php

namespace Tests\Unit\Fakes;

use App\Domain\Entities\ProgramEntity;
use App\Domain\Repositories\IProgramRepository;

class FakeProgramRepository implements IProgramRepository
{
    private array $programs = [];
    private int $nextId = 1;

    // Test control properties
    public bool $hasProjectsForProgram = false;

    public function findById(int $id): ?ProgramEntity
    {
        return $this->programs[$id] ?? null;
    }

    public function findByName(string $name): ?ProgramEntity
    {
        foreach ($this->programs as $program) {
            if ($program->name === $name) {
                return $program;
            }
        }
        return null;
    }

    public function exists(string $name): bool
    {
        return $this->findByName($name) !== null;
    }

    public function existsCaseInsensitive(string $name): bool
    {
        foreach ($this->programs as $program) {
            if (strtolower($program->name) === strtolower($name)) {
                return true;
            }
        }
        return false;
    }

    public function save(ProgramEntity $program): ProgramEntity
    {
        if ($program->id === null) {
            $program->id = $this->nextId++;
        }
        
        $this->programs[$program->id] = $program;
        return $program;
    }

    public function delete(int $id): void
    {
        unset($this->programs[$id]);
    }

    public function hasProjects(int $programId): bool
    {
        return $this->hasProjectsForProgram;
    }

    public function getAll(): array
    {
        return array_values($this->programs);
    }

    // Test helper methods
    public function addProgram(ProgramEntity $program): ProgramEntity
    {
        return $this->save($program);
    }

    public function setHasProjects(bool $hasProjects): void
    {
        $this->hasProjectsForProgram = $hasProjects;
    }

    public function clear(): void
    {
        $this->programs = [];
        $this->nextId = 1;
        $this->hasProjectsForProgram = false;
    }
}