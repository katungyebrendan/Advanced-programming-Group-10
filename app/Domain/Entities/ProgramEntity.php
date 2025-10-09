<?php

namespace App\Domain\Entities;

class ProgramEntity
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public string $description = '',
        public ?string $nationalAlignment = null,
        public array $focusAreas = [],
        public array $phases = []
    ) {}

    public function hasValidNationalAlignment(): bool
    {
        if (empty($this->focusAreas) || empty($this->nationalAlignment)) {
            return true; // Rule only applies when both are present
        }
        
        $validAlignments = ['NDPIII', 'DigitalRoadmap2023_2028', '4IR'];
        $alignmentTokens = explode(',', str_replace(' ', '', $this->nationalAlignment));
        
        foreach ($alignmentTokens as $token) {
            if (in_array(trim($token), $validAlignments)) {
                return true;
            }
        }
        
        return false;
    }

    public function isValid(): array
    {
        $errors = [];

        if (empty($this->name)) {
            $errors[] = 'Program.Name is required.';
        }

        if (empty($this->description)) {
            $errors[] = 'Program.Description is required.';
        }

        if (!$this->hasValidNationalAlignment()) {
            $errors[] = 'Program.NationalAlignment must include at least one recognized alignment when FocusAreas are specified.';
        }

        return $errors;
    }
}