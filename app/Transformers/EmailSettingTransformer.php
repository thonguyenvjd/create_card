<?php

namespace App\Transformers;

use App\Models\EmailSetting;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class EmailSettingTransformer extends Transformer
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
     * @param  \App\Models\EmailSetting $emailSetting
     * @return array
     */
    public function transform(EmailSetting $emailSetting)
    {
        return [
            'id'                => $emailSetting->id,
            'user_id'           => $emailSetting->user_id,
            'from_name'         => $emailSetting->from_name,
            'from_address'      => $emailSetting->from_address,
            'username'          => $emailSetting->username,
            'host'              => $emailSetting->host,
            'port'              => $emailSetting->port,
            'encryption'        => $emailSetting->encryption,
            'cc_email'          => $emailSetting->cc_email,
            'created_at'        => Carbon::parse($emailSetting->created_at)->format('Y/m/d H:i'),
            'updated_at'        => Carbon::parse($emailSetting->updated_at)->format('Y/m/d H:i'),
        ];
    }
}
