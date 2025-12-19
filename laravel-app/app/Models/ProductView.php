<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductView extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'visitor_identifier',
        'user_id',
        'ip_address',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this visitor already viewed this product today
     */
    public static function hasViewedToday($productId, $visitorIdentifier)
    {
        return static::where('product_id', $productId)
            ->where('visitor_identifier', $visitorIdentifier)
            ->whereDate('viewed_at', Carbon::today())
            ->exists();
    }

    /**
     * Record a product view
     */
    public static function recordView($productId, $visitorIdentifier, $userId = null, $ipAddress = null)
    {
        // Only record if not viewed today
        if (!static::hasViewedToday($productId, $visitorIdentifier)) {
            return static::create([
                'product_id' => $productId,
                'visitor_identifier' => $visitorIdentifier,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'viewed_at' => now(),
            ]);
        }

        return null;
    }
}
