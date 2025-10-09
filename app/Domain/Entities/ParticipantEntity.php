<?php

namespace App\Domain\Entities;

class ParticipantEntity
{
    public function __construct(
        public ?int $id = null,
        public string $fullName = '',
        public string $email = '',
        public string $affiliation = '',
        public ?string $specialization = null,
        public bool $crossSkillTrained = false,
        public ?string $institution = null,
        public ?string $description = null
    ) {}

    public function isValid(): array
    {
        $errors = [];

        if (empty($this->fullName)) {
            $errors[] = 'Participant.FullName is required.';
        }

        if (empty($this->email)) {
            $errors[] = 'Participant.Email is required.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Participant.Email must be a valid email address.';
        }

        if (empty($this->affiliation)) {
            $errors[] = 'Participant.Affiliation is required.';
        }

        $validAffiliations = ['CS', 'SE', 'Engineering', 'Other'];
        if (!in_array($this->affiliation, $validAffiliations)) {
            $errors[] = 'Participant.Affiliation must be one of: ' . implode(', ', $validAffiliations);
        }

        // Specialization Requirement Rule
        if ($this->crossSkillTrained && empty($this->specialization)) {
            $errors[] = 'Cross-skill flag requires Specialization.';
        }

        if (!empty($this->specialization)) {
            $validSpecializations = ['Software', 'Hardware', 'Business'];
            if (!in_array($this->specialization, $validSpecializations)) {
                $errors[] = 'Participant.Specialization must be one of: ' . implode(', ', $validSpecializations);
            }
        }

        return $errors;
    }

    public function getEmailKey(): string
    {
        return strtolower($this->email);
    }
}