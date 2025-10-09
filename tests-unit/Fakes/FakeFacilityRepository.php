<?php

namespace Tests\Unit\Fakes;

use App\Domain\Entities\FacilityEntity;
use App\Domain\Repositories\IFacilityRepository;

class FakeFacilityRepository implements IFacilityRepository
{
    private array $facilities = [];
    private int $nextId = 1;

    // Test control properties
    public bool $hasServicesForFacility = false;
    public bool $hasEquipmentForFacility = false;
    public bool $hasProjectsForFacility = false;

    public function findById(int $id): ?FacilityEntity
    {
        return $this->facilities[$id] ?? null;
    }

    public function existsByNameAndLocation(string $name, string $location): bool
    {
        $searchKey = strtolower($name . '|' . $location);
        
        foreach ($this->facilities as $facility) {
            if ($facility->getUniqueKey() === $searchKey) {
                return true;
            }
        }
        
        return false;
    }

    public function save(FacilityEntity $facility): FacilityEntity
    {
        if ($facility->id === null) {
            $facility->id = $this->nextId++;
        }
        
        $this->facilities[$facility->id] = $facility;
        return $facility;
    }

    public function delete(int $id): void
    {
        unset($this->facilities[$id]);
    }

    public function hasServices(int $facilityId): bool
    {
        return $this->hasServicesForFacility;
    }

    public function hasEquipment(int $facilityId): bool
    {
        return $this->hasEquipmentForFacility;
    }

    public function hasProjects(int $facilityId): bool
    {
        return $this->hasProjectsForFacility;
    }

    public function getAll(): array
    {
        return array_values($this->facilities);
    }

    // Test helper methods
    public function addFacility(FacilityEntity $facility): FacilityEntity
    {
        return $this->save($facility);
    }

    public function setHasServices(bool $hasServices): void
    {
        $this->hasServicesForFacility = $hasServices;
    }

    public function setHasEquipment(bool $hasEquipment): void
    {
        $this->hasEquipmentForFacility = $hasEquipment;
    }

    public function setHasProjects(bool $hasProjects): void
    {
        $this->hasProjectsForFacility = $hasProjects;
    }

    public function clear(): void
    {
        $this->facilities = [];
        $this->nextId = 1;
        $this->hasServicesForFacility = false;
        $this->hasEquipmentForFacility = false;
        $this->hasProjectsForFacility = false;
    }
}