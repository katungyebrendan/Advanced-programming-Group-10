<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }

    // REMOVE this conflicting relationship - use participants() many-to-many instead
    // public function participants()
    // {
    //     return $this->hasMany(Participant::class, 'project_id');
    // }

    public function outcomes(): HasMany
    {
        return $this->hasMany(Outcome::class, 'project_id');
    }

    // CORRECTED many-to-many relationship with participants
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(
            Participant::class,
            'project_participants',
            'project_id',
            'participant_id'
        ) //->using(ProjectParticipant::class) // Add this if you have the pivot model
         ->withPivot('role_on_project', 'skill_role')
         ->withTimestamps();
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

    // Add scope to filter by participant
    public function scopeByParticipant(Builder $query, int $participantId): Builder
    {
        return $query->whereHas('participants', function ($q) use ($participantId) {
            $q->where('participant_id', $participantId);
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
            $reasons[] = 'has participants assigned';
        }
        
        if ($this->outcomes()->count() > 0) {
            $reasons[] = 'has outcomes recorded';
        }

        if (empty($reasons)) {
            return 'Project can be deleted';
        }

        return 'Project cannot be deleted because it ' . implode(' and ', $reasons) . '.';
    }

    // 🔹 Business Rules Enforcement
    protected static function boot()
    {
        parent::boot();
        
        // Team Tracking Rule validation on save
        static::saving(function ($project) {
            // This will be checked in the request validation instead
            // as we need to handle the many-to-many relationship properly
        });
    }

    // 🔹 Business Rules Validation Methods
    
    // Team Tracking Rule - Project must have at least one team member
    public function hasRequiredTeamMembers(): bool
    {
        return $this->participants()->count() > 0;
    }

    // Outcome Validation Rule - If Status is 'Completed', at least one Outcome must be attached
    public function hasRequiredOutcomes(): bool
    {
        if ($this->status !== 'Completed') {
            return true;
        }
        return $this->outcomes()->count() > 0;
    }

    // Facility Compatibility Rule
    public function isFacilityCompatible(): bool
    {
        if (empty($this->testing_requirements)) {
            return true; // No requirements to check
        }
        
        $facility = $this->facility;
        if (!$facility || empty($facility->capabilities)) {
            return false;
        }
        
        // This is a simplified check - you might want more sophisticated logic
        // to match testing requirements with facility capabilities
        return true;
    }

    // Status transition validation
    public function canTransitionToStatus(string $newStatus): array
    {
        $errors = [];
        
        if ($newStatus === 'Completed') {
            if (!$this->hasRequiredTeamMembers()) {
                $errors[] = 'Project must have at least one team member assigned.';
            }
            
            if (!$this->hasRequiredOutcomes()) {
                $errors[] = 'Completed projects must have at least one documented outcome.';
            }
        }
        
        return $errors;
    }

    public function getProgressPercentage(): int
    {
        $stages = self::PROTOTYPE_STAGES;
        $currentIndex = array_search($this->prototype_stage, $stages);
        
        if ($currentIndex === false) return 0;
        
        return (int) (($currentIndex + 1) / count($stages) * 100);
    }

    // New methods for managing participants
    public function addParticipant(Participant $participant, ?string $role = null, ?string $skillRole = null): void
    {
        $this->participants()->attach($participant->participant_id, [
            'role_on_project' => $role,
            'skill_role' => $skillRole
        ]);
    }

    public function removeParticipant(Participant $participant): void
    {
        $this->participants()->detach($participant->participant_id);
    }

    public function updateParticipantRole(Participant $participant, ?string $role = null, ?string $skillRole = null): void
    {
        $this->participants()->updateExistingPivot($participant->participant_id, [
            'role_on_project' => $role,
            'skill_role' => $skillRole
        ]);
    }

    public function getParticipantCount(): int
    {
        return $this->participants()->count();
    }

    public function hasParticipant(Participant $participant): bool
    {
        return $this->participants()->where('participant_id', $participant->participant_id)->exists();
    }

    public function getParticipantsByRole(string $role): \Illuminate\Database\Eloquent\Collection
    {
        return $this->participants()->wherePivot('role_on_project', $role)->get();
    }

    public function getLecturers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->getParticipantsByRole('Lecturer');
    }

    public function getStudents(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->getParticipantsByRole('Student');
    }

    public function hasLecturer(): bool
    {
        return $this->getLecturers()->count() > 0;
    }

    public function canAddMoreParticipants(int $max = 10): bool
    {
        return $this->getParticipantCount() < $max;
    }
}