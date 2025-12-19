<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SiteVisit;
use App\Helpers\AnalyticsHelper;
use Illuminate\Support\Facades\Auth;

class TrackSiteVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking for admin routes, API routes, and AJAX requests
        if ($request->is('admin/*') ||
            $request->is('api/*') ||
            $request->ajax() ||
            $request->expectsJson()) {
            return $next($request);
        }

        // Get or create visitor identifier
        $visitorIdentifier = AnalyticsHelper::getVisitorIdentifier();

        // Get user ID if authenticated
        $userId = Auth::check() ? Auth::id() : null;

        // Record the visit (with throttling built into the model)
        try {
            SiteVisit::recordVisit(
                $visitorIdentifier,
                $userId,
                $request->fullUrl(),
                $request->header('referer'),
                $request->ip(),
                $request->userAgent()
            );
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to track site visit: ' . $e->getMessage());
        }

        return $next($request);
    }
}
