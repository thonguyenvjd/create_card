<?php

namespace App\Services;

use App\Models\DistributionTracking;
use App\Models\DistributionDetail;
use Illuminate\Support\Str;

class EmailTrackingService
{
    public function processEmailContent($content, $distributionHistoryId, $distributionDetailId)
    {
        preg_match_all('/<a[^>]*href=["\']([^"\']*)["\'][^>]*>(.*?)<\/a>/i', $content, $matches);
        
        $urlsData = [];
        if (!empty($matches[1]) || !empty($matches[2])) {
            foreach ($matches[1] as $key => $href) {
                $url = !empty($href) ? $href : $matches[2][$key];
                if (!empty($url)) {
                    $urlsData[] = [
                        'url'           => $url,
                        'total_clicks'  => 0,
                    ];
                }
            }
        }

        if (!empty($urlsData)) {
            DistributionDetail::where('id', $distributionDetailId)->update([
                'url_clicked' => json_encode($urlsData)
            ]);
        }

        return preg_replace_callback(
            '/<a[^>]*href=["\']([^"\']*)["\'][^>]*>(.*?)<\/a>/i',
            function ($matches) use ($distributionHistoryId, $distributionDetailId) {
                $href = $matches[1];
                $text = $matches[2];
                
                $originalUrl = !empty($href) ? $href : $text;
                
                if (empty($originalUrl)) {
                    return $matches[0]; 
                }

                $trackingUrl = $this->generateTrackingUrl(
                    $originalUrl, 
                    $distributionHistoryId, 
                    $distributionDetailId
                );

                return str_replace(
                    'href="' . $href . '"',
                    'href="' . $trackingUrl . '"',
                    $matches[0]
                );
            },
            $content
        );
    }

    public function generateTrackingUrl($originalUrl, $distributionHistoryId, $distributionDetailId)
    {
        $token = Str::random(32);
        
        DistributionTracking::create([
            'distribution_history_id'   => $distributionHistoryId,
            'distribution_detail_id'    => $distributionDetailId,
            'tracking_token'            => $token,
            'type'                      => 'click',
            'original_url'              => $originalUrl,
        ]);

        return route('email.track', ['token' => $token]);
    }

    public function generateOpenTrackingPixel($distributionHistoryId, $distributionDetailId)
    {
        $token = Str::random(32);
        
        DistributionTracking::create([
            'distribution_history_id'   => $distributionHistoryId,
            'distribution_detail_id'    => $distributionDetailId,
            'tracking_token'            => $token,
            'type'                      => 'open'
        ]);

        return route('email.track.pixel', ['token' => $token]);
    }
} 
