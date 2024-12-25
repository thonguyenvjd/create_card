<?php

namespace App\Transformers;

use App\Models\Template;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class TemplateTransformer extends Transformer
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
     * @param  \App\Models\Template $template
     * @return array
     */
    public function transform(Template $template)
    {
        return [
            'id'                    => $template->id,
            'content'               => $template->content,
            'created_at'            => Carbon::parse($template->created_at)->format('Y/m/d H:i:s'),
            'updated_at'            => Carbon::parse($template->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
