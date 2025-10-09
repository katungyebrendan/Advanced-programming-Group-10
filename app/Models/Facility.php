<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Facility extends Model
{
    protected $primaryKey = 'facility_id';

    protected $fillable = [
        'name',
        'location',
        'description',
        'partner_organization',
        'facility_type',
        'capabilities',
    ];

    protected $casts = [
        'capabilities' => 'array', // This will convert the comma-separated string to a JSON array
    ];

    // Relationships
    public function services()
    {
        return $this->hasMany(Service::class, 'facility_id');
    }

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'facility_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'facility_id');
    }

    // 🔹 Query scopes
    public function scopeByType(Builder $query, ?string $type): Builder
    {
        return $type ? $query->where('facility_type', $type) : $query;
    }

    public function scopeByPartner(Builder $query, ?string $partner): Builder
    {
        return $partner ? $query->where('partner_organization', $partner) : $query;
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('location', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }

    public function scopeWithCapability(Builder $query, string $capability): Builder
    {
        return $query->whereJsonContains('capabilities', $capability);
    }

    // 🔹 Safeguard methods
    public function canBeDeleted(): bool
    {
        // Deletion Constraints Rule - check Services, Equipment, and Projects
        return $this->services()->count() === 0 && 
               $this->equipment()->count() === 0 && 
               $this->projects()->count() === 0;
    }

    public function getDeletionBlockReason(): string
    {
        $reasons = [];
        
        if ($this->services()->count() > 0) {
            $reasons[] = 'Services';
        }
        if ($this->equipment()->count() > 0) {
            $reasons[] = 'Equipment';
        }
        if ($this->projects()->count() > 0) {
            $reasons[] = 'Projects';
        }
        
        if (empty($reasons)) {
            return 'Facility can be deleted.';
        }
        
        return 'Facility has dependent records (' . implode('/', $reasons) . ').';
    }

    // 🔹 Business Rules Enforcement
    protected static function boot()
    {
        parent::boot();
        
        // Deletion Constraints Rule
        static::deleting(function ($facility) {
            if (!$facility->canBeDeleted()) {
                throw new \Exception($facility->getDeletionBlockReason());
            }
        });
    }

    // Capabilities validation
    public function hasRequiredCapabilities(): bool
    {
        $hasServices = $this->services()->count() > 0;
        $hasEquipment = $this->equipment()->count() > 0;
        
        if ($hasServices || $hasEquipment) {
            return !empty($this->capabilities) && count($this->capabilities) > 0;
        }
        
        return true; // No requirement if no services or equipment
    }
}