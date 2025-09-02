<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id',
        'name',
        'capabilities',
        'description',
        'inventory_code',
        'usage_domain',
        'support_phase',
    ];

    // Relationship: Equipment belongs to a Facility
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
