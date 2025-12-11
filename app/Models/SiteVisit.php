<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SiteVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_identifier',
        'user_id',
        'page_url',
        'referer',
        'ip_address',
        'user_agent',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a site visit with throttling (once per 30 minutes per visitor)
     */
    public static function recordVisit($visitorIdentifier, $userId = null, $pageUrl = null, $referer = null, $ipAddress = null, $userAgent = null)
    {
        // Check if visitor has visited in the last 30 minutes
        $recentVisit = static::where('visitor_identifier', $visitorIdentifier)
            ->where('visited_at', '>', Carbon::now()->subMinutes(30))
            ->exists();

        if (!$recentVisit) {
            return static::create([
                'visitor_identifier' => $visitorIdentifier,
                'user_id' => $userId,
                'page_url' => $pageUrl,
                'referer' => $referer,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'visited_at' => now(),
            ]);
        }

        return null;
    }
}
