<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\FacilityEntity;
use App\Domain\Repositories\IFacilityRepository;
use App\Models\Facility;

class EloquentFacilityRepository implements IFacilityRepository
{
    private function normalizeCapabilities(mixed $val): array
    {
        if (is_array($val)) {
            return $val;
        }
        if (is_null($val)) {
            return [];
        }
        if (is_string($val)) {
            $trim = trim($val);
            $decoded = json_decode($trim, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            $parts = array_filter(array_map('trim', explode(',', $trim)));
            return array_values($parts);
        }
        return [];
    }
    public function findById(int $id): ?FacilityEntity
    {
        $model = Facility::find($id);
        if (!$model) return null;

        return new FacilityEntity(
            $model->facility_id,
            $model->name,
            $model->location,
            $model->facility_type,
            $model->description,
            $model->partner_organization,
            $this->normalizeCapabilities($model->capabilities)
        );
    }

    public function existsByNameAndLocation(string $name, string $location): bool
    {
        return Facility::whereRaw('lower(name) = ? and lower(location) = ?', [strtolower($name), strtolower($location)])->exists();
    }

    public function findByNameAndLocation(string $name, string $location): ?FacilityEntity
    {
        $m = Facility::whereRaw('lower(name) = ? and lower(location) = ?', [strtolower($name), strtolower($location)])->first();
        if (!$m) return null;

        return new FacilityEntity(
            $m->facility_id,
            $m->name,
            $m->location,
            $m->facility_type,
            $m->description,
            $m->partner_organization,
            $this->normalizeCapabilities($m->capabilities)
        );
    }

    public function save(FacilityEntity $facility): FacilityEntity
    {
        $model = $facility->id ? Facility::find($facility->id) : new Facility();

    $model->name = $facility->name;
    $model->location = $facility->location;
    $model->description = $facility->description;
    $model->partner_organization = $facility->partnerOrganization;
    $model->facility_type = $facility->facilityType;
    $model->capabilities = is_array($facility->capabilities) ? $facility->capabilities : $this->normalizeCapabilities($facility->capabilities);

        $model->save();

        return new FacilityEntity(
            $model->facility_id,
            $model->name,
            $model->location,
            $model->facility_type,
            $model->description,
            $model->partner_organization,
            $this->normalizeCapabilities($model->capabilities)
        );
    }

    public function delete(int $id): void
    {
        Facility::destroy($id);
    }

    public function hasServices(int $facilityId): bool
    {
        $f = Facility::find($facilityId);
        return $f ? $f->services()->exists() : false;
    }

    public function hasEquipment(int $facilityId): bool
    {
        $f = Facility::find($facilityId);
        return $f ? $f->equipment()->exists() : false;
    }

    public function hasProjects(int $facilityId): bool
    {
        $f = Facility::find($facilityId);
        return $f ? $f->projects()->exists() : false;
    }

    public function getAll(): array
    {
        return Facility::all()->map(function ($m) {
            return new FacilityEntity(
                $m->facility_id,
                $m->name,
                $m->location,
                $m->facility_type,
                $m->description,
                $m->partner_organization,
                $this->normalizeCapabilities($m->capabilities)
            );
        })->all();
    }
}
