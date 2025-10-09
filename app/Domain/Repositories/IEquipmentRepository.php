<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\EquipmentEntity;

interface IEquipmentRepository
{
    public function findById(int $id): ?EquipmentEntity;
    public function existsByInventoryCode(string $inventoryCode): bool;
    public function save(EquipmentEntity $equipment): EquipmentEntity;
    public function delete(int $id): void;
    public function isReferencedByActiveProject(int $equipmentId, int $facilityId): bool;
    public function getAll(): array;
}