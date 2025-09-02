<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Facility extends Model
{
    protected $primaryKey = 'facility_id';

    protected $fillable = [
        'name',
        'location',
        'description',
        'partner_organization',
        'facility_type',
        'capabilities',
    ];

    protected $casts = [
        'capabilities' => 'array', // store as JSON
    ];

    // Relationships
    public function services()
    {
        return $this->hasMany(Service::class, 'facility_id');
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'facility_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'facility_id');
    }

    // Query scopes
    public function scopeByType(Builder $query, ?string $type): Builder
    {
        return $type ? $query->where('facility_type', $type) : $query;
    }

    public function scopeByPartner(Builder $query, ?string $partner): Builder
    {
        return $partner ? $query->where('partner_organization', $partner) : $query;
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('location', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }

    public function scopeWithCapability(Builder $query, string $capability): Builder
    {
        return $query->whereJsonContains('capabilities', $capability);
    }

    // Safeguard methods
    public function canBeDeleted(): bool
    {
        // Block deletion if linked to any projects
        return $this->projects()->count() === 0;
    }

    public function getDeletionBlockReason(): string
    {
        if ($this->projects()->count() > 0) {
            return 'Facility cannot be deleted because it has active projects.';
        }
        return 'Unknown reason';
    }
}