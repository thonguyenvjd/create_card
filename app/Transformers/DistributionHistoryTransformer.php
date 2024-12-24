<?php

namespace App\Transformers;

use App\Models\DistributionHistory;
use App\Models\Group;
use App\Models\RecipientFilter;
use League\Fractal\TransformerAbstract;
use Carbon\Carbon;

class DistributionHistoryTransformer extends TransformerAbstract
{
    public function transform(DistributionHistory $history)
    {
        $urlClicks = [];
        
        foreach ($history->distributionDetails as $detail) {
            $urlsData = json_decode($detail->url_clicked, true) ?? [];
            foreach ($urlsData as $urlData) {
                $url = $urlData['url'];
                if (!isset($urlClicks[$url])) {
                    $urlClicks[$url] = [
                        'url' => $url,
                        'total_clicks' => 0,
                        'user_clicks' => []
                    ];
                }
                $urlClicks[$url]['total_clicks'] += $urlData['total_clicks'];
                
                if (isset($urlData['details'])) {
                    foreach ($urlData['details'] as $email => $count) {
                        if (!isset($urlClicks[$url]['user_clicks'][$email])) {
                            $urlClicks[$url]['user_clicks'][$email] = 0;
                        }
                        $urlClicks[$url]['user_clicks'][$email] += $count;
                    }
                }
            }
        }

        uasort($urlClicks, function($a, $b) {
            return $b['total_clicks'] <=> $a['total_clicks'];
        });

        $openRate = $history->success_count > 0 
            ? round(($history->open_count / $history->success_count) * 100, 2) 
            : 0;

        return [
            'id'        => $history->id,
            'subject'   => $history->reservation->subject,
            'type'      => $history->reservation->email_type,
            'address'   => $this->parseAddressTo($history->reservation->address_to_type, $history->reservation->address_to),
            'source_address' => $history->reservation->emailSetting->from_name . ' <' . $history->reservation->emailSetting->from_address . '>',
            'delivery_status' => [
                'number_of_delivery'    => $history->number_of_delivery,
                'success_count'         => $history->success_count,
                'failed_count'          => $history->failed_count,
            ],
            'tracking_status' => [
                'open_count'        => $history->open_count,
                'open_rate'         => $openRate > 0 ? "{$openRate}%/{$history->success_count}" : '-',
                'total_urls'        => count($urlClicks),
                'click_count'       => $history->click_count,
                'click_details'     => array_values($urlClicks)
            ],
            'created_at' => Carbon::parse($history->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($history->updated_at)->format('Y-m-d H:i:s')
        ];
    }

    private function parseAddressTo(string $addressToType, int $addressToId = null)
    {
        if ($addressToType === 'all') {
            return '全登録者';
        } elseif ($addressToType === 'group') {
            $group = Group::find($addressToId)->name;

            return $group;
        } elseif ($addressToType === 'filter') {
            $filter = RecipientFilter::find($addressToId)->name;

            return $filter;
        }

        return $addressToType;
    }
} 
