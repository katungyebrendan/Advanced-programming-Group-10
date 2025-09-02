<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Outcome extends Model
{
    protected $primaryKey = 'outcome_id';

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'artifact_link',
        'outcome_type',
        'quality_certification',
        'commercialization_status',
    ];

    // Define enum values
    const OUTCOME_TYPES = ['CAD', 'PCB', 'Prototype', 'Report', 'Business Plan'];
    const COMMERCIALIZATION_STATUSES = ['Demoed', 'Market Linked', 'Launched'];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // 🔹 Query scopes
    public function scopeByProject(Builder $query, ?int $projectId): Builder
    {
        return $projectId ? $query->where('project_id', $projectId) : $query;
    }

    public function scopeByType(Builder $query, ?string $type): Builder
    {
        return $type ? $query->where('outcome_type', $type) : $query;
    }

    public function scopeByCommercializationStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('commercialization_status', $status) : $query;
    }

    public function scopeWithArtifacts(Builder $query): Builder
    {
        return $query->whereNotNull('artifact_link')->where('artifact_link', '!=', '');
    }

    public function scopeCertified(Builder $query): Builder
    {
        return $query->whereNotNull('quality_certification')->where('quality_certification', '!=', '');
    }

    public function scopeCommercializable(Builder $query): Builder
    {
        return $query->whereIn('outcome_type', ['Prototype', 'Business Plan']);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }

    // 🔹 Business logic methods
    public function canBeDeleted(): bool
    {
        // Outcomes that are launched or have certifications should be preserved for audit
        return !$this->isLaunched() && !$this->hasCertification();
    }

    public function getDeletionBlockReason(): string
    {
        if ($this->isLaunched()) {
            return 'Cannot delete launched outcomes as they are required for commercialization tracking.';
        }
        
        if ($this->hasCertification()) {
            return 'Cannot delete certified outcomes as they are required for quality assurance records.';
        }
        
        return 'Unknown reason';
    }

    public function hasArtifact(): bool
    {
        return !empty($this->artifact_link);
    }

    public function hasCertification(): bool
    {
        return !empty($this->quality_certification);
    }

    public function isCommercializable(): bool
    {
        return in_array($this->outcome_type, ['Prototype', 'Business Plan']);
    }

    public function isLaunched(): bool
    {
        return $this->commercialization_status === 'Launched';
    }

    public function isMarketReady(): bool
    {
        return in_array($this->commercialization_status, ['Market Linked', 'Launched']);
    }

    public function canBeDownloaded(): bool
    {
        return $this->hasArtifact() && $this->isAccessibleFile();
    }

    private function isAccessibleFile(): bool
    {
        // Check if the artifact link points to a downloadable file
        return filter_var($this->artifact_link, FILTER_VALIDATE_URL) !== false;
    }

    public function getCommercializationProgress(): int
    {
        $progressMap = [
            null => 0,
            'Demoed' => 33,
            'Market Linked' => 66,
            'Launched' => 100,
        ];

        return $progressMap[$this->commercialization_status] ?? 0;
    }

    public function requiresQualityAssurance(): bool
    {
        // Physical outcomes typically need quality certification
        return in_array($this->outcome_type, ['Prototype', 'PCB']);
    }

    public function getFileExtension(): ?string
    {
        if (!$this->hasArtifact()) {
            return null;
        }

        return pathinfo($this->artifact_link, PATHINFO_EXTENSION);
    }

    public function getDisplayStatus(): string
    {
        $status = $this->commercialization_status ?? 'In Development';
        $certified = $this->hasCertification() ? ' (Certified)' : '';
        
        return $status . $certified;
    }

    public function getAuditTrail(): array
    {
        return [
            'outcome_id' => $this->outcome_id,
            'project_id' => $this->project_id,
            'type' => $this->outcome_type,
            'has_artifact' => $this->hasArtifact(),
            'certified' => $this->hasCertification(),
            'commercialization_status' => $this->commercialization_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}