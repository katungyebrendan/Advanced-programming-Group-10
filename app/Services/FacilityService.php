<?php

// ===== 1. app/Services/FacilityService.php =====
// This is the  business logic layer 

namespace App\Services;

use App\Models\Facility;
use InvalidArgumentException;

class FacilityService
{
    /**
     * Create a new facility with business validation
     */
    public function createFacility(array $data): Facility
    {
        // Business validation (independent of HTTP validation)
        $this->validateBusinessRules($data);

        // Check if facility name already exists
        if (Facility::where('name', $data['name'])->exists()) {
            throw new InvalidArgumentException("Facility with name '{$data['name']}' already exists");
        }

        // Create the facility
        $facility = Facility::create([
            'name' => $data['name'],
            'location' => $data['location'],
            'description' => $data['description'],
            'partner_organization' => $data['partner_organization'] ?? null,
            'facility_type' => $data['facility_type'],
            'capabilities' => $data['capabilities'] ?? [],
        ]);

        return $facility;
    }

    /**
     * Get all facilities with optional filtering
     */
    public function getFacilities(?string $type = null, ?string $partner = null): array
    {
        $query = Facility::query();

        if ($type) {
            $query->where('facility_type', $type);
        }

        if ($partner) {
            $query->where('partner_organization', $partner);
        }

        return $query->get()->toArray();
    }

    /**
     * Get a facility by ID
     */
    public function getFacilityById(int $facilityId): ?Facility
    {
        return Facility::find($facilityId);
    }

    /**
     * Update facility
     */
    public function updateFacility(int $facilityId, array $data): Facility
    {
        $facility = Facility::findOrFail($facilityId);
        
        $this->validateBusinessRules($data);

        // Check name uniqueness (excluding current facility)
        if (Facility::where('name', $data['name'])
                  ->where('facility_id', '!=', $facilityId)
                  ->exists()) {
            throw new InvalidArgumentException("Facility with name '{$data['name']}' already exists");
        }

        $facility->update([
            'name' => $data['name'],
            'location' => $data['location'],
            'description' => $data['description'],
            'partner_organization' => $data['partner_organization'] ?? null,
            'facility_type' => $data['facility_type'],
            'capabilities' => $data['capabilities'] ?? [],
        ]);

        return $facility->fresh();
    }

    /**
     * Delete facility (with business rules check)
     */
    public function deleteFacility(int $facilityId): bool
    {
        $facility = Facility::findOrFail($facilityId);

        // Business rule: Can't delete facility with active projects
        if ($facility->projects()->exists()) {
            throw new InvalidArgumentException("Cannot delete facility with active projects");
        }

        return $facility->delete();
    }

    /**
     * Business validation rules (independent of HTTP layer)
     */
    private function validateBusinessRules(array $data): void
    {
        if (empty(trim($data['name'] ?? ''))) {
            throw new InvalidArgumentException("Facility name is required");
        }

        if (empty(trim($data['location'] ?? ''))) {
            throw new InvalidArgumentException("Facility location is required");
        }

        if (empty(trim($data['description'] ?? ''))) {
            throw new InvalidArgumentException("Facility description is required");
        }

        $validTypes = ['Lab', 'Workshop', 'Testing Center', 'Maker Space'];
        if (!in_array($data['facility_type'] ?? '', $validTypes)) {
            throw new InvalidArgumentException("Invalid facility type");
        }

        if (!empty($data['partner_organization'])) {
            $validPartners = ['UniPod', 'UIRI', 'Lwera'];
            if (!in_array($data['partner_organization'], $validPartners)) {
                throw new InvalidArgumentException("Invalid partner organization");
            }
        }

        if (isset($data['capabilities']) && !is_array($data['capabilities'])) {
            throw new InvalidArgumentException("Capabilities must be an array");
        }
    }
}
