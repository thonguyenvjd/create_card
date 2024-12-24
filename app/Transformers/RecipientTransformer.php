<?php

namespace App\Transformers;

use App\Models\Recipient;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class RecipientTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = ['groups'];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Models\Recipient $recipient
     * @return array
     */
    public function transform(Recipient $recipient)
    {
        return [
            'id'                => $recipient->id,
            'email'             => $recipient->email,     
            'name'              => $recipient->name,
            'situation'         => (int) $recipient->situation,
            'number_of_error'   => $recipient->number_of_error,
            'groups'            => $recipient->groups->map(function ($group) {
                return [
                    'id'    => $group->id,
                    'name'  => $group->name
                ];
            }),
            'created_at'        => Carbon::parse($recipient->created_at)->format('Y/m/d H:i:s'),
            'updated_at'        => Carbon::parse($recipient->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
