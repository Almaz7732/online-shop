<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $table = 'about';

    protected $fillable = [
        'title',
        'content',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get active about record
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
     * Boot the model and add model events
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a new record
        static::creating(function ($model) {
            if ($model->is_active) {
                // Deactivate all other records
                static::where('is_active', true)->update(['is_active' => false]);
            }
        });

        // When updating an existing record
        static::updating(function ($model) {
            if ($model->is_active && $model->isDirty('is_active')) {
                // Deactivate all other records except this one
                static::where('is_active', true)->where('id', '!=', $model->id)->update(['is_active' => false]);
            }
        });
    }
}
