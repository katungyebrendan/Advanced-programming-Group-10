<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\ParticipantEntity;

interface IParticipantRepository
{
    public function findById(int $id): ?ParticipantEntity;
    public function existsByEmail(string $email): bool;
    public function save(ParticipantEntity $participant): ParticipantEntity;
    public function delete(int $id): void;
    public function hasActiveProjects(int $participantId): bool;
    public function getAll(): array;
}