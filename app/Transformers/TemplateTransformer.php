<?php

namespace App\Transformers;

use App\Models\Group;
use App\Models\RecipientFilter;
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
            'name'                  => $template->name,     
            'subject'               => $template->subject,
            'content'               => $template->content,
            'type'                  => $template->type,
            'image'                 => $template->image,
            'address_to'            => $this->parseAddressTo($template->address_to_type, $template->address_to),
            'address_to_id'         => (int) $template->address_to,
            'address_to_type'       => $template->address_to_type,
            'date'                  => ! empty($template->scheduled_at) ? Carbon::parse($template->scheduled_at)->format('Y/m/d') : null  ,
            'hour'                  => ! empty($template->scheduled_at) ? Carbon::parse($template->scheduled_at)->format('H') : null,
            'minute'                => ! empty($template->scheduled_at) ? Carbon::parse($template->scheduled_at)->format('i') : null,
            'created_at'            => Carbon::parse($template->created_at)->format('Y/m/d H:i:s'),
            'updated_at'            => Carbon::parse($template->updated_at)->format('Y/m/d H:i:s'),
        ];
    }

    private function parseAddressTo(string $addressToType, int $addressToId = null)
    {
        if ($addressToType === 'all') {
            return '全登録者';
        } elseif ($addressToType === 'group') {
            $group = Group::find($addressToId)->name;

            return $group;
        } elseif ($addressToType === 'filter') {
            $filter = RecipientFilter::find($addressToId)->name;

            return $filter;
        }

        return $addressToType;
    }
}
