<?php

namespace App\Services;

use InvalidArgumentException;

class FacilityService
{
    private JsonDB $db;

    public function __construct()
    {
        $this->db = new JsonDB('facilities'); // storage/json/facilities.json
    }

    /**
     * Get all facilities (with optional filters)
     */
    public function getFacilities(?string $type = null, ?string $partner = null): array
    {
        $facilities = collect($this->db->all());

        if ($type) {
            $facilities = $facilities->where('facility_type', $type);
        }

        if ($partner) {
            $facilities = $facilities->where('partner_organization', $partner);
        }

        return $facilities->values()->toArray();
    }

    /**
     * Get a single facility
     */
    public function getFacilityById(int $id): ?array
    {
        return $this->db->find($id);
    }

    /**
     * Create facility
     */
    public function createFacility(array $data): array
    {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Facility name is required.');
        }

        return $this->db->create($data);
    }

    /**
     * Update facility
     */
    public function updateFacility(int $id, array $data): ?array
    {
        $facility = $this->db->update($id, $data);

        if (!$facility) {
            throw new InvalidArgumentException("Facility with ID {$id} not found.");
        }

        return $facility;
    }

    /**
     * Delete facility
     */
    public function deleteFacility(int $id): bool
    {
        $deleted = $this->db->delete($id);

        if (!$deleted) {
            throw new InvalidArgumentException("Facility with ID {$id} cannot be deleted or not found.");
        }

        return true;
    }
}
