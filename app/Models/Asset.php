<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',          // Add this
        'asset_tag',
        'delivery_date',
        'category',
        'brand',
        'provider',
        'status',
        'specification',
        'remarks',
    ];

    // Optional: Add constants for companies
    const COMPANIES = ['NEBG', 'FA'];

    // Optional: Scopes for filtering
    public function scopeCompany($query, $company)
    {
        return $query->where('company', $company);
    }

    public function scopeNebg($query)
    {
        return $query->where('company', 'NEBG');
    }

    public function scopeFa($query)
    {
        return $query->where('company', 'FA');
    }

    // Optional: Accessor for company badge color
    public function getCompanyBadgeColorAttribute()
    {
        return $this->company === 'NEBG' ? 'primary' : 'success';
    }

    // Optional: Accessor for company badge icon
    public function getCompanyIconAttribute()
    {
        return $this->company === 'NEBG' ? '🏢' : '🏭';
    }
}