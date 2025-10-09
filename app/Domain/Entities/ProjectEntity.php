<?php

namespace App\Domain\Entities;

class ProjectEntity
{
    public function __construct(
        public ?int $id = null,
        public int $programId = 0,
        public int $facilityId = 0,
        public string $title = '',
        public ?string $natureOfProject = null,
        public ?string $description = null,
        public ?string $innovationFocus = null,
        public ?string $prototypeStage = null,
        public ?string $testingRequirements = null,
        public ?string $commercializationPlan = null,
        public string $status = 'Planning'
    ) {}

    public function isValid(): array
    {
        $errors = [];

        if ($this->programId <= 0) {
            $errors[] = 'Project.ProgramId is required.';
        }

        if ($this->facilityId <= 0) {
            $errors[] = 'Project.FacilityId is required.';
        }

        if (empty($this->title)) {
            $errors[] = 'Project.Title is required.';
        }

        return $errors;
    }

    public function getProgramScopedUniqueKey(): string
    {
        return $this->programId . '|' . strtolower($this->title);
    }

    public function canBeCompleted(bool $hasTeamMembers, bool $hasOutcomes): array
    {
        $errors = [];

        if (!$hasTeamMembers) {
            $errors[] = 'Project must have at least one team member assigned.';
        }

        if (!$hasOutcomes) {
            $errors[] = 'Completed projects must have at least one documented outcome.';
        }

        return $errors;
    }
}