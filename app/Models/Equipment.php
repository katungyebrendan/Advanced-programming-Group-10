<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'name',
        'capabilities',
        'description',
        'inventory_code',
        'usage_domain',
        'support_phase',
    ];

    // Relationship: Equipment belongs to a Facility
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }

    // Relationships for projects using this equipment
    public function projects()
    {
        return $this->facility->projects();
    }

    // 🔹 Business Rules Enforcement
    protected static function boot()
    {
        parent::boot();
        
        // Delete Guard Rule - Equipment cannot be deleted if referenced by active Project
        static::deleting(function ($equipment) {
            if (!$equipment->canBeDeleted()) {
                throw new \Exception('Equipment referenced by active Project.');
            }
        });
    }

    // 🔹 Safeguard methods
    public function canBeDeleted(): bool
    {
        // Check if equipment is referenced by any active project in the same facility
        // This is a simplified check - you might want more sophisticated logic
        return $this->facility->projects()->count() === 0;
    }

    public function getDeletionBlockReason(): string
    {
        if ($this->facility->projects()->count() > 0) {
            return 'Equipment referenced by active Project.';
        }
        return 'Equipment can be deleted.';
    }

    // Usage Domain-Support Phase Coherence validation
    public function hasValidUsageDomainCoherence(): bool
    {
        if ($this->usage_domain !== 'Electronics' || empty($this->support_phase)) {
            return true; // Rule only applies to Electronics equipment with support phase
        }
        
        $supportPhases = array_map('trim', explode(',', $this->support_phase));
        $validPhases = ['Prototyping', 'Testing'];
        
        // Check if it's Training only
        $isTrainingOnly = count($supportPhases) === 1 && in_array('Training', $supportPhases);
        
        if ($isTrainingOnly) {
            return false;
        }
        
        // Check if it has at least one valid phase
        foreach ($supportPhases as $phase) {
            if (in_array($phase, $validPhases)) {
                return true;
            }
        }
        
        return false;
    }

}