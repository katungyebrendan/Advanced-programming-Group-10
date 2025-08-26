<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service;
use App\Models\Equipment;
use App\Models\Project;

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
        'capabilities' => 'array', // store as JSON
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
}
