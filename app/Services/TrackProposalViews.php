<?php
// app/Services/TrackProposalViews.php

namespace App\Services;

use App\Models\ProposalViews;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class TrackProposalViews
{
    public function trackPageView(?int $recordId = null): void
    {
        // $visitorId = $this->getOrCreateVisitorId();
       if(!auth()->user()){
        ProposalViews::create([
            'proposal_id' => $recordId,
            'page_url' => request()->fullUrl(),
            'ip_hash' => request()->ip(),
            'user_agent' => request()->header('user-agent'),
            'browser' => $this->getBrowser(),
            'viewed_at' => now(),
        ]);
       }
        
    }
    
    // private function getOrCreateVisitorId(): string
    // {
    //     $cookieName = 'anonymous_visitor_id';
        
    //     if (Cookie::has($cookieName)) {
    //         return Cookie::get($cookieName);
    //     }
        
    //     $visitorId = Str::uuid()->toString();
    //     Cookie::queue($cookieName, $visitorId, 525600); // 1 year
        
    //     return $visitorId;
    // }
    
    private function getBrowser(): string
    {
        $userAgent = request()->header('user-agent', '');
        
        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Edge')) return 'Edge';
        
        return 'Unknown';
    }
    
    private function getDeviceType(): string
    {
        $userAgent = request()->header('user-agent', '');
        
        if (preg_match('/mobile|android|iphone|ipad|tablet/i', $userAgent)) {
            return 'Mobile';
        }
        
        return 'Desktop';
    }
}