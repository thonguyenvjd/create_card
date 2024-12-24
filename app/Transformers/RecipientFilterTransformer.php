<?php

namespace App\Transformers;

use App\Models\RecipientFilter;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class RecipientFilterTransformer extends Transformer
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
     * @param  \App\Models\RecipientFilter $recipientFilter
     * @return array
     */
    public function transform(RecipientFilter $recipientFilter)
    {
        return [
            'id'                => $recipientFilter->id,
            'user_id'           => $recipientFilter->user_id,
            'name'              => $recipientFilter->name,
            'conditions'        => $recipientFilter->conditions,
            'created_at'        => Carbon::parse($recipientFilter->created_at)->format('Y/m/d H:i'),
            'updated_at'        => Carbon::parse($recipientFilter->updated_at)->format('Y/m/d H:i'),
        ];
    }
}
