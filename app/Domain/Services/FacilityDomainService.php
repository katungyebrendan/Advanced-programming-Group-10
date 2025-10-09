<?php

namespace App\Domain\Services;

use App\Domain\Entities\FacilityEntity;
use App\Domain\Repositories\IFacilityRepository;

class FacilityDomainService
{
    public function __construct(
        private IFacilityRepository $facilityRepository
    ) {}

    public function createFacility(FacilityEntity $facility): array
    {
        // Validate entity
        $errors = $facility->isValid();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Business Rule: Uniqueness (Name + Location combination)
        if ($this->facilityRepository->existsByNameAndLocation($facility->name, $facility->location)) {
            return ['success' => false, 'errors' => ['A facility with this name already exists at this location.']];
        }

        // Save facility
        $savedFacility = $this->facilityRepository->save($facility);
        
        return ['success' => true, 'facility' => $savedFacility];
    }

    public function updateFacility(FacilityEntity $facility): array
    {
        // Validate entity
        $errors = $facility->isValid();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Business Rule: Capabilities must be populated when Services/Equipment exist
        if (empty($facility->capabilities)) {
            $hasServices = $this->facilityRepository->hasServices($facility->id);
            $hasEquipment = $this->facilityRepository->hasEquipment($facility->id);
            
            if ($hasServices || $hasEquipment) {
                return ['success' => false, 'errors' => ['Facility.Capabilities must be populated when Services/Equipment exist.']];
            }
        }

        // Save facility
        $savedFacility = $this->facilityRepository->save($facility);
        
        return ['success' => true, 'facility' => $savedFacility];
    }

    public function deleteFacility(int $facilityId): array
    {
        // Business Rule: Deletion Constraints
        $hasServices = $this->facilityRepository->hasServices($facilityId);
        $hasEquipment = $this->facilityRepository->hasEquipment($facilityId);
        $hasProjects = $this->facilityRepository->hasProjects($facilityId);

        if ($hasServices || $hasEquipment || $hasProjects) {
            $reasons = [];
            if ($hasServices) $reasons[] = 'Services';
            if ($hasEquipment) $reasons[] = 'Equipment';
            if ($hasProjects) $reasons[] = 'Projects';
            
            return ['success' => false, 'errors' => ['Facility has dependent records (' . implode('/', $reasons) . ').']];
        }

        $this->facilityRepository->delete($facilityId);
        
        return ['success' => true];
    }
}