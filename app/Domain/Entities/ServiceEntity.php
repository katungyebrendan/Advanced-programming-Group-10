<?php

namespace App\Domain\Entities;

class ServiceEntity
{
    public function __construct(
        public ?int $id = null,
        public int $facilityId = 0,
        public string $name = '',
        public string $category = '',
        public string $skillType = '',
        public ?string $description = null
    ) {}

    public function isValid(): array
    {
        $errors = [];

        if ($this->facilityId <= 0) {
            $errors[] = 'Service.FacilityId is required.';
        }

        if (empty($this->name)) {
            $errors[] = 'Service.Name is required.';
        }

        if (empty($this->category)) {
            $errors[] = 'Service.Category is required.';
        }

        if (empty($this->skillType)) {
            $errors[] = 'Service.SkillType is required.';
        }

        $validCategories = ['Machining', 'Testing', 'Training'];
        if (!in_array($this->category, $validCategories)) {
            $errors[] = 'Service.Category must be one of: ' . implode(', ', $validCategories);
        }

        $validSkillTypes = ['Hardware', 'Software', 'Integration'];
        if (!in_array($this->skillType, $validSkillTypes)) {
            $errors[] = 'Service.SkillType must be one of: ' . implode(', ', $validSkillTypes);
        }

        return $errors;
    }

    public function getScopedUniqueKey(): string
    {
        return $this->facilityId . '|' . strtolower($this->name);
    }
}