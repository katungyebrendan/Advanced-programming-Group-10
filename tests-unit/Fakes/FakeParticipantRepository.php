<?php

namespace Tests\Unit\Fakes;

use App\Domain\Entities\ParticipantEntity;
use App\Domain\Repositories\IParticipantRepository;

class FakeParticipantRepository implements IParticipantRepository
{
    private array $participants = [];
    private int $nextId = 1;

    // Test control properties
    public bool $hasActiveProjectsForParticipant = false;

    public function findById(int $id): ?ParticipantEntity
    {
        return $this->participants[$id] ?? null;
    }

    public function existsByEmail(string $email): bool
    {
        $searchKey = strtolower($email);
        
        foreach ($this->participants as $participant) {
            if ($participant->getEmailKey() === $searchKey) {
                return true;
            }
        }
        
        return false;
    }

    public function save(ParticipantEntity $participant): ParticipantEntity
    {
        if ($participant->id === null) {
            $participant->id = $this->nextId++;
        }
        
        $this->participants[$participant->id] = $participant;
        return $participant;
    }

    public function delete(int $id): void
    {
        unset($this->participants[$id]);
    }

    public function hasActiveProjects(int $participantId): bool
    {
        return $this->hasActiveProjectsForParticipant;
    }

    public function getAll(): array
    {
        return array_values($this->participants);
    }

    // Test helper methods
    public function addParticipant(ParticipantEntity $participant): ParticipantEntity
    {
        return $this->save($participant);
    }

    public function setHasActiveProjects(bool $hasActiveProjects): void
    {
        $this->hasActiveProjectsForParticipant = $hasActiveProjects;
    }

    public function clear(): void
    {
        $this->participants = [];
        $this->nextId = 1;
        $this->hasActiveProjectsForParticipant = false;
    }
}