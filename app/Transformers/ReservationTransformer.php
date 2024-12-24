<?php

namespace App\Transformers;

use App\Models\Group;
use App\Models\Recipient;
use App\Models\RecipientFilter;
use App\Models\Reservation;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class ReservationTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Models\Reservation $reservation
     * @return array
     */
    public function transform(Reservation $reservation)
    {
        return [
            'id'                    => $reservation->id,
            'user_id'               => $reservation->user_id,
            'source_address'        => $reservation->emailSetting->from_address,
            'email_setting_id'      => $reservation->email_setting_id,
            'address_to'            => $this->parseAddressTo($reservation->address_to_type, $reservation->address_to),
            'address_to_id'         => (int) $reservation->address_to,
            'address_to_type'       => $reservation->address_to_type,
            'subject'               => $reservation->subject,
            'content'               => $reservation->content,
            'email_type'            => $reservation->email_type,
            'delivery_type'         => $reservation->delivery_type,
            'delivery_status'       => $reservation->delivery_status,
            'number_of_delivery'    => $this->getNumberOfDelivery($reservation),
            'is_draft'              => (int) $reservation->is_draft,
            'is_sent'               => (int) $reservation->is_sent,
            'is_click_measure'      => (int) $reservation->is_click_measure,
            'date'                  => ! empty($reservation->scheduled_at) ? Carbon::parse($reservation->scheduled_at)->format('Y/m/d') : null  ,
            'hour'                  => ! empty($reservation->scheduled_at) ? Carbon::parse($reservation->scheduled_at)->format('H') : null,
            'minute'                => ! empty($reservation->scheduled_at) ? Carbon::parse($reservation->scheduled_at)->format('i') : null,
            'created_at'            => Carbon::parse($reservation->created_at)->format('Y/m/d H:i'),
            'updated_at'            => Carbon::parse($reservation->updated_at)->format('Y/m/d H:i'),
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

    private function getNumberOfDelivery(Reservation $reservation)
    {
        if ($reservation->address_to_type === 'group') {
            return Recipient::whereHas('recipientAssigns', function ($query) use ($reservation) {
                $query->where('group_id', $reservation->address_to);
            })
            ->where('user_id', $reservation->user_id)
            ->count();
        } elseif ($reservation->address_to_type === 'all') {
            return Recipient::where('user_id', $reservation->user_id)->count();
        } elseif ($reservation->address_to_type === 'filter') {
            $count = app('App\Repositories\RecipientRepository')
                ->getFilterCount($reservation->address_to, $reservation->user_id);
            return (int) $count;
        }

        return $reservation->number_of_delivery;
    }
}
