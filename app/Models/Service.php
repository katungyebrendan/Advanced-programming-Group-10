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
        // Delete Guard Rule - Services cannot be deleted if any Project at that Facility 
        // references its Category in TestingRequirements
        $projects = $this->facility->projects();
        
        foreach ($projects as $project) {
            if (!empty($project->testing_requirements) && 
                stripos($project->testing_requirements, $this->category) !== false) {
                return false;
            }
        }
        
        return true;
    }

    public function getDeletionBlockReason(): string
    {
        if (!$this->canBeDeleted()) {
            return 'Service in use by Project testing requirements.';
        }
        return 'Service can be deleted.';
    }

    // 🔹 Business Rules Enforcement
    protected static function boot()
    {
        parent::boot();
        
        // Delete Guard Rule
        static::deleting(function ($service) {
            if (!$service->canBeDeleted()) {
                throw new \Exception('Service in use by Project testing requirements.');
            }
        });
    }

    public function getDisplayName(): string
    {
        return "{$this->name} ({$this->category} - {$this->skill_type})";
    }
}