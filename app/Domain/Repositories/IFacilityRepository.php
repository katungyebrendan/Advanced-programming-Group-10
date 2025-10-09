<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\FacilityEntity;

interface IFacilityRepository
{
    public function findById(int $id): ?FacilityEntity;
    public function existsByNameAndLocation(string $name, string $location): bool;
    public function save(FacilityEntity $facility): FacilityEntity;
    public function delete(int $id): void;
    public function hasServices(int $facilityId): bool;
    public function hasEquipment(int $facilityId): bool;
    public function hasProjects(int $facilityId): bool;
    public function getAll(): array;
}