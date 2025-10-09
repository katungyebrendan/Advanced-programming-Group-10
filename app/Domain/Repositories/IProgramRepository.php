<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\ProgramEntity;

interface IProgramRepository
{
    public function findById(int $id): ?ProgramEntity;
    public function findByName(string $name): ?ProgramEntity;
    public function exists(string $name): bool;
    public function existsCaseInsensitive(string $name): bool;
    public function save(ProgramEntity $program): ProgramEntity;
    public function delete(int $id): void;
    public function hasProjects(int $programId): bool;
    public function getAll(): array;
}