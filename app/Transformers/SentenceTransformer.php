<?php

namespace App\Transformers;

use App\Models\Sentence;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class SentenceTransformer extends Transformer
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
    public function transform(Sentence $sentence)
    {
        return [
            'id'         => $sentence->id,
            'name'       => $sentence->name,     
            'content'    => $sentence->content,
            'created_at' => Carbon::parse($sentence->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Carbon::parse($sentence->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
