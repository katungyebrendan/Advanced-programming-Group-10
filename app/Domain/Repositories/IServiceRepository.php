<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\ServiceEntity;

interface IServiceRepository
{
    public function findById(int $id): ?ServiceEntity;
    public function existsByNameInFacility(string $name, int $facilityId): bool;
    public function save(ServiceEntity $service): ServiceEntity;
    public function delete(int $id): void;
    public function isReferencedByProjectTestingRequirements(string $category, int $facilityId): bool;
    public function getAll(): array;
}