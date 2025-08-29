<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    protected $primaryKey = 'project_id';

    protected $fillable = [
        'title',
        'description',
        'facility_id',
        'program_id',
        'innovation_focus',
        'prototype_stage',
        'commercialization_plan',
        'participants',  // JSON array
    ];

    protected $casts = [
        'participants' => 'array',
    ];

    // 🔹 Relationships
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function outcomes()
    {
        return $this->hasMany(Outcome::class, 'project_id');
    }

    // 🔹 Query scopes
    public function scopeByFacility(Builder $query, ?int $facilityId): Builder
    {
        return $facilityId ? $query->where('facility_id', $facilityId) : $query;
    }

    public function scopeByProgram(Builder $query, ?int $programId): Builder
    {
        return $programId ? $query->where('program_id', $programId) : $query;
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhere('innovation_focus', 'LIKE', "%{$term}%");
        });
    }

    // 🔹 Safeguard methods
    public function canBeDeleted(): bool
    {
        return count($this->outcomes) === 0;
    }

    public function getDeletionBlockReason(): string
    {
        return count($this->outcomes) > 0
            ? 'Project cannot be deleted because it has outcomes.'
            : 'Unknown reason';
    }
}
