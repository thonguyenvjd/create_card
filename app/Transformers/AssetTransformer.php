<?php

namespace App\Transformers;

use App\Models\Asset;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class AssetTransformer extends Transformer
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
     * @param  \App\Models\Asset $asset
     * @return array
     */
    public function transform(Asset $asset)
    {
        return [
            'id'         => $asset->id,
            'name'       => $asset->name,
            'src'        => $asset->src,
            'type'       => $asset->type,
            'created_at' => Carbon::parse($asset->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Carbon::parse($asset->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
