<?php

namespace App\Domain\Entities;

class FacilityEntity
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $location = '',
        public string $facilityType = '',
        public ?string $description = null,
        public ?string $partnerOrganization = null,
        public array $capabilities = []
    ) {}

    public function isValid(): array
    {
        $errors = [];

        if (empty($this->name)) {
            $errors[] = 'Facility.Name is required.';
        }

        if (empty($this->location)) {
            $errors[] = 'Facility.Location is required.';
        }

        if (empty($this->facilityType)) {
            $errors[] = 'Facility.FacilityType is required.';
        }

        return $errors;
    }

    public function getUniqueKey(): string
    {
        return strtolower($this->name . '|' . $this->location);
    }
}