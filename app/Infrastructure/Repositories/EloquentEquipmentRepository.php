<?php
namespace App\Infrastructure\Repositories;

use App\Domain\Entities\EquipmentEntity;
use App\Domain\Repositories\IEquipmentRepository;
use App\Models\Equipment;

class EloquentEquipmentRepository implements IEquipmentRepository
{
    public function findById(int $id): ?EquipmentEntity
    {
        $model = Equipment::find($id);
        if (!$model) return null;
        return new EquipmentEntity(
            $model->id,
            $model->facility_id,
            $model->name,
            $model->inventory_code,
            $model->description,
            $model->capabilities,
            $model->usage_domain,
            $model->support_phase
        );
    }
    public function existsByInventoryCode(string $inventoryCode): bool
    {
        return Equipment::where('inventory_code', $inventoryCode)->exists();
    }
    public function save(EquipmentEntity $equipment): EquipmentEntity
    {
        $model = $equipment->id ? Equipment::find($equipment->id) : new Equipment();
        $model->facility_id = $equipment->facilityId;
        $model->name = $equipment->name;
        $model->inventory_code = $equipment->inventoryCode;
        $model->description = $equipment->description;
        $model->capabilities = $equipment->capabilities;
        $model->usage_domain = $equipment->usageDomain;
        $model->support_phase = $equipment->supportPhase;
        $model->save();
        return new EquipmentEntity(
            $model->id,
            $model->facility_id,
            $model->name,
            $model->inventory_code,
            $model->description,
            $model->capabilities,
            $model->usage_domain,
            $model->support_phase
        );
    }
    public function delete(int $id): void
    {
        Equipment::destroy($id);
    }
    public function isReferencedByActiveProject(int $equipmentId, int $facilityId): bool
    {
        // Implement as needed
        return false;
    }
    public function getAll(): array
    {
        return Equipment::all()->map(function ($m) {
            return new EquipmentEntity(
                $m->id,
                $m->facility_id,
                $m->name,
                $m->inventory_code,
                $m->description,
                $m->capabilities,
                $m->usage_domain,
                $m->support_phase
            );
        })->all();
    }
}
