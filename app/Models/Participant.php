<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Participant extends Model
{
    protected $primaryKey = 'participant_id';

    protected $fillable = [
        'full_name',
        'email',
        'affiliation',
        'specialization',
        'cross_skill_trained',
        'institution',
    ];

    protected $casts = [
        'cross_skill_trained' => 'boolean',
    ];

    const AFFILIATIONS = ['CS', 'SE', 'Engineering', 'Other'];
    const SPECIALIZATIONS = ['Software', 'Hardware', 'Business'];
    const INSTITUTIONS = ['SCIT', 'CEDAT', 'UniPod', 'UIRI', 'Lwera'];

    // 🔹 Relationships
    public function projects()
    {
        return $this->belongsToMany(
            Project::class,
            'project_participants',
            'participant_id',
            'project_id'
        )->withPivot('role_on_project', 'skill_role')
         ->withTimestamps();
    }

    // 🔹 Query scopes
    public function scopeByAffiliation(Builder $query, ?string $affiliation): Builder
    {
        return $affiliation ? $query->where('affiliation', $affiliation) : $query;
    }

    public function scopeBySpecialization(Builder $query, ?string $specialization): Builder
    {
        return $specialization ? $query->where('specialization', $specialization) : $query;
    }

    public function scopeByInstitution(Builder $query, ?string $institution): Builder
    {
        return $institution ? $query->where('institution', $institution) : $query;
    }

    public function scopeCrossSkillTrained(Builder $query, ?bool $trained = null): Builder
    {
        return $trained !== null ? $query->where('cross_skill_trained', $trained) : $query;
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'LIKE', "%{$term}%")
              ->orWhere('email', 'LIKE', "%{$term}%");
        });
    }

    // 🔹 Utility methods
    public function canBeDeleted(): bool
    {
        return $this->projects()->count() === 0;
    }

    public function getDeletionBlockReason(): string
    {
        return $this->projects()->count() > 0
            ? 'Participant cannot be deleted because they are assigned to active projects.'
            : 'Unknown reason';
    }

    public function getActiveProjectsCount(): int
    {
        return $this->projects()->count();
    }

    public function isEngagedInProject(int $projectId): bool
    {
        return $this->projects()->where('project_id', $projectId)->exists();
    }

    public function getProjectRoles(): array
    {
        return $this->projects()->withPivot('role_on_project', 'skill_role')->get()
            ->map(fn($project) => [
                'project_title' => $project->title,
                'role' => $project->pivot->role_on_project,
                'skill_role' => $project->pivot->skill_role,
            ])->toArray();
    }

    public function canTakeOnMoreProjects(int $maxProjects = 3): bool
    {
        return $this->getActiveProjectsCount() < $maxProjects;
    }

    public function hasSkillForRole(string $requiredSkill): bool
    {
        return $this->specialization === $requiredSkill || $this->cross_skill_trained;
    }

    public function getDisplayName(): string
    {
        return "{$this->full_name} ({$this->institution})";
    }

    public function getSkillsDescription(): string
    {
        $skills = [$this->specialization];
        if ($this->cross_skill_trained) $skills[] = 'Cross-skilled';
        return implode(', ', $skills);
    }
}
