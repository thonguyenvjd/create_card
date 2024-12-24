<?php

namespace App\Transformers;

use App\Models\Group;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class GroupTransformer extends Transformer
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
     * @param  \App\Models\Group $group
     * @return array
     */
    public function transform(Group $group)
    {
        return [
            'id'         => $group->id,
            'name'       => $group->name,     
            'created_at' => Carbon::parse($group->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Carbon::parse($group->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
