<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }

    // Query scopes
    public function scopeByCategory($query, $category)
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeBySkillType($query, $skillType)
    {
        return $skillType ? $query->where('skill_type', $skillType) : $query;
    }

    public function scopeByFacility($query, $facilityId)
    {
        return $facilityId ? $query->where('facility_id', $facilityId) : $query;
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }

    // Business logic methods
    public function canBeDeleted(): bool
    {
        // Services can typically be deleted unless they're referenced in active projects
        return true;
    }

    public function getDeletionBlockReason(): string
    {
        return 'Service is currently in use and cannot be deleted.';
    }

    public function getDisplayName(): string
    {
        return "{$this->name} ({$this->category} - {$this->skill_type})";
    }
}