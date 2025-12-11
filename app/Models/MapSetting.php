<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_maps_api_key',
        'latitude',
        'longitude',
        'zoom_level',
        'company_address',
        'map_style',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'zoom_level' => 'integer',
    ];

    /**
     * Get active map settings record
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Scope for active records
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get all available map styles
     */
    public static function getMapStyles()
    {
        return [
            'roadmap' => 'Roadmap',
            'satellite' => 'Satellite',
            'hybrid' => 'Hybrid',
            'terrain' => 'Terrain'
        ];
    }

    /**
     * Check if map is configured and ready to display
     */
    public function isConfigured()
    {
        return !empty($this->google_maps_api_key) &&
               !empty($this->latitude) &&
               !empty($this->longitude);
    }
}
