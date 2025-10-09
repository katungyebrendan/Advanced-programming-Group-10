<?php

namespace App\Domain\Entities;

class EquipmentEntity
{
    public function __construct(
        public ?int $id = null,
        public int $facilityId = 0,
        public string $name = '',
        public string $inventoryCode = '',
        public ?string $description = null,
        public ?string $capabilities = null,
        public ?string $usageDomain = null,
        public ?string $supportPhase = null
    ) {}

    public function isValid(): array
    {
        $errors = [];

        if ($this->facilityId <= 0) {
            $errors[] = 'Equipment.FacilityId is required.';
        }

        if (empty($this->name)) {
            $errors[] = 'Equipment.Name is required.';
        }

        if (empty($this->inventoryCode)) {
            $errors[] = 'Equipment.InventoryCode is required.';
        }

        // Usage Domain-Support Phase Coherence Rule
        if ($this->usageDomain === 'Electronics' && !empty($this->supportPhase)) {
            $supportPhases = array_map('trim', explode(',', $this->supportPhase));
            $validPhases = ['Prototyping', 'Testing'];
            
            $hasValidPhase = false;
            foreach ($supportPhases as $phase) {
                if (in_array($phase, $validPhases)) {
                    $hasValidPhase = true;
                    break;
                }
            }
            
            $isTrainingOnly = count($supportPhases) === 1 && in_array('Training', $supportPhases);
            
            if ($isTrainingOnly || !$hasValidPhase) {
                $errors[] = 'Electronics equipment must support Prototyping or Testing.';
            }
        }

        return $errors;
    }
}