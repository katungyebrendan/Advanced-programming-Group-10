<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProjectParticipant extends Model
{
    protected $table = 'project_participants';
    public $incrementing = false;
    protected $primaryKey = ['project_id', 'participant_id'];

    protected $fillable = [
        'project_id',
        'participant_id',
        'role_on_project',
        'skill_role',
    ];

    // Define enum values
    const PROJECT_ROLES = ['Student', 'Lecturer', 'Contributor'];
    const SKILL_ROLES = ['Developer', 'Engineer', 'Designer', 'Business Lead'];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    // 🔹 Query scopes
    public function scopeByProject(Builder $query, ?int $projectId): Builder
    {
        return $projectId ? $query->where('project_id', $projectId) : $query;
    }

    public function scopeByParticipant(Builder $query, ?int $participantId): Builder
    {
        return $participantId ? $query->where('participant_id', $participantId) : $query;
    }

    public function scopeByRole(Builder $query, ?string $role): Builder
    {
        return $role ? $query->where('role_on_project', $role) : $query;
    }

    public function scopeBySkillRole(Builder $query, ?string $skillRole): Builder
    {
        return $skillRole ? $query->where('skill_role', $skillRole) : $query;
    }

    public function scopeStudents(Builder $query): Builder
    {
        return $query->where('role_on_project', 'Student');
    }

    public function scopeLecturers(Builder $query): Builder
    {
        return $query->where('role_on_project', 'Lecturer');
    }

    public function scopeContributors(Builder $query): Builder
    {
        return $query->where('role_on_project', 'Contributor');
    }

    // 🔹 Business logic methods
    public function canBeDeleted(): bool
    {
        // Check if removing this participant would leave the project without essential roles
        return $this->wouldNotBreakProjectStructure();
    }

    public function getDeletionBlockReason(): string
    {
        if (!$this->wouldNotBreakProjectStructure()) {
            return 'Cannot remove participant as it would leave the project without essential supervision or leadership.';
        }
        return 'Unknown reason';
    }

    private function wouldNotBreakProjectStructure(): bool
    {
        // Ensure project has at least one lecturer after removal
        if ($this->role_on_project === 'Lecturer') {
            $lecturerCount = ProjectParticipant::byProject($this->project_id)
                ->lecturers()
                ->where('participant_id', '!=', $this->participant_id)
                ->count();
            return $lecturerCount > 0;
        }

        // Ensure project has at least one business lead if this is the only one
        if ($this->skill_role === 'Business Lead') {
            $businessLeadCount = ProjectParticipant::byProject($this->project_id)
                ->bySkillRole('Business Lead')
                ->where('participant_id', '!=', $this->participant_id)
                ->count();
            return $businessLeadCount > 0;
        }

        return true;
    }

    public function isProjectLead(): bool
    {
        return in_array($this->skill_role, ['Business Lead']) || $this->role_on_project === 'Lecturer';
    }

    public function canModifyRole(): bool
    {
        // Lecturers should maintain supervision role
        return $this->role_on_project !== 'Lecturer';
    }

    public function getPermissionsForProject(): array
    {
        $permissions = ['view_project'];

        if ($this->role_on_project === 'Lecturer') {
            $permissions = array_merge($permissions, [
                'edit_project',
                'assign_participants',
                'approve_outcomes',
                'grade_project'
            ]);
        }

        if ($this->skill_role === 'Business Lead') {
            $permissions = array_merge($permissions, [
                'edit_commercialization_plan',
                'manage_outcomes'
            ]);
        }

        if (in_array($this->role_on_project, ['Student', 'Contributor'])) {
            $permissions = array_merge($permissions, [
                'create_outcomes',
                'edit_own_contributions'
            ]);
        }

        return array_unique($permissions);
    }

    public function getRoleHierarchyLevel(): int
    {
        $hierarchy = [
            'Lecturer' => 3,
            'Contributor' => 2,
            'Student' => 1,
        ];

        return $hierarchy[$this->role_on_project] ?? 0;
    }

    public function getDisplayRole(): string
    {
        return "{$this->role_on_project} ({$this->skill_role})";
    }
}