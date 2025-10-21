<?php
namespace App\Domain\Services;

use App\Domain\Entities\EquipmentEntity;
use App\Domain\Repositories\IEquipmentRepository;

class EquipmentDomainService
{
    public function __construct(private IEquipmentRepository $equipmentRepository) {}

    public function createEquipment(EquipmentEntity $equipment): array
    {
        $errors = $equipment->isValid();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        if ($this->equipmentRepository->existsByInventoryCode($equipment->inventoryCode)) {
            return ['success' => false, 'errors' => ['Inventory code must be unique']];
        }
        $saved = $this->equipmentRepository->save($equipment);
        return ['success' => true, 'equipment' => $saved];
    }

    public function updateEquipment(EquipmentEntity $equipment): array
    {
        $errors = $equipment->isValid();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        $saved = $this->equipmentRepository->save($equipment);
        return ['success' => true, 'equipment' => $saved];
    }

    public function deleteEquipment(int $id): array
    {
        $this->equipmentRepository->delete($id);
        return ['success' => true];
    }

    public function getAll(): array
    {
        return $this->equipmentRepository->getAll();
    }

    public function findById(int $id): ?EquipmentEntity
    {
        return $this->equipmentRepository->findById($id);
    }
}
