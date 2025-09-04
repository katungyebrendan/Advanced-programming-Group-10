<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    protected $primaryKey = 'project_id';
    
    protected $fillable = [
        'program_id',
        'facility_id',
        'title',
        'nature_of_project',
        'description',
        'innovation_focus',
        'prototype_stage',
        'testing_requirements',
        'commercialization_plan',
    ];

    // Define available options as constants
    const NATURE_OPTIONS = ['Research', 'Prototype', 'Applied Work'];
    const PROTOTYPE_STAGES = ['Concept', 'Prototype', 'MVP', 'Market Launch'];
    const INNOVATION_FOCUS_OPTIONS = [
        'IoT Devices',
        'Smart Home',
        'Renewable Energy',
        'Software Development',
        'Mobile Applications',
        'Web Applications',
        'Automation Systems',
        'Data Analytics',
        'Artificial Intelligence',
        'Machine Learning'
    ];

    // Relationships
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class, 'project_id');
    }

    public function outcomes()
    {
        return $this->hasMany(Outcome::class, 'project_id');
    }

    // Query scopes
    public function scopeByProgram(Builder $query, $programId): Builder
    {
        return $query->where('program_id', $programId);
    }

    public function scopeByFacility(Builder $query, $facilityId): Builder
    {
        return $query->where('facility_id', $facilityId);
    }

    public function scopeByNature(Builder $query, string $nature): Builder
    {
        return $query->where('nature_of_project', $nature);
    }

    public function scopeByStage(Builder $query, string $stage): Builder
    {
        return $query->where('prototype_stage', $stage);
    }

    public function scopeByInnovationFocus(Builder $query, string $focus): Builder
    {
        return $query->where('innovation_focus', $focus);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhere('innovation_focus', 'LIKE', "%{$term}%");
        });
    }

    // Utility methods
    public function canBeDeleted(): bool
    {
        // Can be deleted if no participants or outcomes
        return $this->participants()->count() === 0 && $this->outcomes()->count() === 0;
    }

    public function getDeletionBlockReason(): string
    {
        $reasons = [];
        
        if ($this->participants()->count() > 0) {
            $reasons[] = 'has participants';
        }
        
        if ($this->outcomes()->count() > 0) {
            $reasons[] = 'has outcomes';
        }

        if (empty($reasons)) {
            return 'Unknown reason';
        }

        return 'Project cannot be deleted because it ' . implode(' and ', $reasons) . '.';
    }

    public function participant()
{
    return $this->belongsToMany(
        Participant::class,
        'project_participants',
        'project_id',
        'participant_id'
    )->withPivot('role_on_project', 'skill_role')
     ->withTimestamps();
}

    public function getProgressPercentage(): int
    {
        $stages = ['Concept', 'Prototype', 'MVP', 'Market Launch'];
        $currentIndex = array_search($this->prototype_stage, $stages);
        
        if ($currentIndex === false) return 0;
        
        return (int) (($currentIndex + 1) / count($stages) * 100);
    }
}

