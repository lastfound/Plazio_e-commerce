<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\StoreTrackingLink;
use App\Models\TrackingLog;
use Symfony\Component\HttpFoundation\Response;

class TrackReferralLink
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $code = $request->query('ref');
            $link = StoreTrackingLink::where('code', $code)->first();

            if ($link) {
                // Save referral code in session for conversion tracking during checkout
                session(['active_tracking_code' => $code, 'active_tracking_link_id' => $link->id]);

                // Avoid logging duplicate click for same session within 10 minutes
                $sessionKey = 'tracked_link_' . $link->id;
                if (!session()->has($sessionKey)) {
                    $link->increment('clicks_count');

                    TrackingLog::create([
                        'store_tracking_link_id' => $link->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->header('User-Agent'),
                        'referrer' => $request->header('referer'),
                        'event_type' => 'click'
                    ]);

                    session([$sessionKey => true]);
                }
            }
        }

        return $next($request);
    }
}
