<?php

namespace App\Transformers;

use App\Models\User;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class UserTransformer extends Transformer
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
     * @param  \App\Models\User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'         => $user->id,
            'email'      => $user->email,
            'name'       => $user->name,     
            'created_at' => Carbon::parse($user->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Carbon::parse($user->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
