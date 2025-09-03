<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    protected $primaryKey = 'service_id';

    protected $fillable = [
        'facility_id',
        'name',
        'description',
        'category',
        'skill_type',
    ];

    // Define enum values as constants
    const CATEGORIES = ['Machining', 'Testing', 'Training'];
    const SKILL_TYPES = ['Hardware', 'Software', 'Integration'];

    // Relationships
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    // 🔹 Query scopes
    public function scopeByCategory(Builder $query, ?string $category): Builder
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeBySkillType(Builder $query, ?string $skillType): Builder
    {
        return $skillType ? $query->where('skill_type', $skillType) : $query;
    }

    public function scopeByFacility(Builder $query, ?int $facilityId): Builder
    {
        return $facilityId ? $query->where('facility_id', $facilityId) : $query;
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }

    // 🔹 Business logic methods
    public function canBeDeleted(): bool
    {
        // Services can typically be deleted unless they're referenced in active projects
        // This would require additional logic if you track service usage in projects
        return true;
    }

    public function getDeletionBlockReason(): string
    {
        // Placeholder for future constraints
        return 'Service is currently in use and cannot be deleted.';
    }

    public function matchesProjectRequirements(array $requirements): bool
    {
        // Helper method to match services to project requirements
        if (isset($requirements['category']) && $this->category !== $requirements['category']) {
            return false;
        }
        
        if (isset($requirements['skill_type']) && $this->skill_type !== $requirements['skill_type']) {
            return false;
        }
        
        return true;
    }

    public function getDisplayName(): string
    {
        return "{$this->name} ({$this->category} - {$this->skill_type})";
    }
}