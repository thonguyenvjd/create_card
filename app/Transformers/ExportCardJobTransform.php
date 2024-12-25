<?php

namespace App\Transformers;

use App\Models\ExportCardJob;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class ExportCardJobTransform extends Transformer
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
     * @param  \App\Models\ExportCardJob $exportCardJob
     * @return array
     */
    public function transform(ExportCardJob $exportCardJob)
    {
        return [
            'id'                    => $exportCardJob->id,
            'file_path'             => $exportCardJob->file_path,
            'status'                => $exportCardJob->status,
            'created_at'            => Carbon::parse($exportCardJob->created_at)->format('Y/m/d H:i:s'),
            'updated_at'            => Carbon::parse($exportCardJob->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
