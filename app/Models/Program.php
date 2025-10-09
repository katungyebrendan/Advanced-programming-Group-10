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
        // Lifecycle Protection Rule - Prevent deletion if linked to projects
        return $this->projects()->count() === 0;
    }

    public function getDeletionBlockReason(): string
    {
        if ($this->projects()->count() > 0) {
            return 'Program has Projects; archive or reassign before delete.';
        }
        return 'Unknown reason';
    }

    // 🔹 Business Rules Enforcement
    protected static function boot()
    {
        parent::boot();
        
        // Lifecycle Protection Rule
        static::deleting(function ($program) {
            if (!$program->canBeDeleted()) {
                throw new \Exception('Program has Projects; archive or reassign before delete.');
            }
        });
    }

    // National Alignment validation helper
    public function hasValidNationalAlignment(): bool
    {
        if (empty($this->focus_areas) || empty($this->national_alignment)) {
            return true; // Rule only applies when both are present
        }
        
        $validAlignments = ['NDPIII', 'DigitalRoadmap2023_2028', '4IR'];
        $alignmentTokens = explode(',', str_replace(' ', '', $this->national_alignment));
        
        foreach ($alignmentTokens as $token) {
            if (in_array(trim($token), $validAlignments)) {
                return true;
            }
        }
        
        return false;
    }
}
