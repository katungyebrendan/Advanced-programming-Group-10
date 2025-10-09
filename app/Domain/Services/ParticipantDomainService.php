<?php

namespace App\Domain\Services;

use App\Domain\Entities\ParticipantEntity;
use App\Domain\Repositories\IParticipantRepository;

class ParticipantDomainService
{
    public function __construct(
        private IParticipantRepository $participantRepository
    ) {}

    public function createParticipant(ParticipantEntity $participant): array
    {
        // Validate entity
        $errors = $participant->isValid();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Business Rule: Email Uniqueness (case-insensitive)
        if ($this->participantRepository->existsByEmail($participant->email)) {
            return ['success' => false, 'errors' => ['Participant.Email already exists.']];
        }

        // Save participant
        $savedParticipant = $this->participantRepository->save($participant);
        
        return ['success' => true, 'participant' => $savedParticipant];
    }

    public function updateParticipant(ParticipantEntity $participant): array
    {
        // Validate entity
        $errors = $participant->isValid();
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Save participant
        $savedParticipant = $this->participantRepository->save($participant);
        
        return ['success' => true, 'participant' => $savedParticipant];
    }

    public function deleteParticipant(int $participantId): array
    {
        // Business Rule: Cannot delete if has active projects
        if ($this->participantRepository->hasActiveProjects($participantId)) {
            return ['success' => false, 'errors' => ['Participant cannot be deleted because they are assigned to active projects.']];
        }

        $this->participantRepository->delete($participantId);
        
        return ['success' => true];
    }
}