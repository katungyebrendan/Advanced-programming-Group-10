<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Program extends Model
{
    protected $primaryKey = 'program_id';

    protected $fillable = [
        'name',
        'description',
        'national_alignment',
        'focus_areas',   // JSON
        'phases',        // JSON
    ];

    protected $casts = [
        'focus_areas' => 'array',
        'phases' => 'array',
    ];

    // 🔹 Relationships
    public function projects()
    {
        return $this->hasMany(Project::class, 'program_id');
    }

    // 🔹 Query scopes
    public function scopeByAlignment(Builder $query, ?string $alignment): Builder
    {
        return $alignment ? $query->where('national_alignment', $alignment) : $query;
    }

    public function scopeWithFocusArea(Builder $query, string $focus): Builder
    {
        return $query->whereJsonContains('focus_areas', $focus);
    }

    public function scopeWithPhase(Builder $query, string $phase): Builder
    {
        return $query->whereJsonContains('phases', $phase);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }

    // 🔹 Safeguard methods
    public function canBeDeleted(): bool
    {
        // Prevent deletion if linked to projects
        return $this->projects()->count() === 0;
    }

    public function getDeletionBlockReason(): string
    {
        if ($this->projects()->count() > 0) {
            return 'Program cannot be deleted because it has active projects.';
        }
        return 'Unknown reason';
    }
}
