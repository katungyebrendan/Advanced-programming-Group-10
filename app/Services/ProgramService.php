<?php

namespace App\Services;

use InvalidArgumentException;

class ProgramService
{
    private JsonDB $db;

    public function __construct()
    {
        $this->db = new JsonDB('programs'); // storage/json/programs.json
    }

    public function getPrograms(): array
    {
        return $this->db->all();
    }

    public function getProgramById(int $id): ?array
    {
        return $this->db->find($id);
    }

    public function createProgram(array $data): array
    {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Program name is required.');
        }

        return $this->db->create($data);
    }

    public function updateProgram(int $id, array $data): ?array
    {
        $program = $this->db->update($id, $data);

        if (!$program) {
            throw new InvalidArgumentException("Program with ID {$id} not found.");
        }

        return $program;
    }

    public function deleteProgram(int $id): bool
    {
        $deleted = $this->db->delete($id);

        if (!$deleted) {
            throw new InvalidArgumentException("Program with ID {$id} not found or cannot be deleted.");
        }

        return true;
    }
}
