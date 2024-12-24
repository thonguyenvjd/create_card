<?php

namespace App\Transformers;

use App\Models\RecipientJob;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class RecipientJobTransformer extends Transformer
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
     * @param  \App\Models\RecipientJob $recipientJob
     * @return array
     */
    public function transform(RecipientJob $recipientJob)
    {
        return [
            'id'                => $recipientJob->id,
            'user_id'           => $recipientJob->user_id,
            'name'              => $recipientJob->name,
            'success_count'     => $recipientJob->success_count,
            'error_count'       => $recipientJob->error_count,
            'error_message'     => $recipientJob->error_message,
            'file_path'         => $recipientJob->file_path,
            'status'            => $recipientJob->status,
            'created_at'        => Carbon::parse($recipientJob->created_at)->format('Y/m/d H:i'),
            'updated_at'        => Carbon::parse($recipientJob->updated_at)->format('Y/m/d H:i'),
        ];
    }
}
