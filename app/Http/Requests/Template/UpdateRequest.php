<?php

namespace App\Http\Requests\Template;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name'              => 'sometimes',
            'subject'           => 'sometimes',
            'content'           => 'sometimes',
            'type'              => 'sometimes',
            'image'             => 'nullable',
            'email_setting_id'  => 'nullable',
            'address_to'        => 'nullable',
            'address_to_type'   => 'nullable',
            'scheduled_at'      => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'              => __('messages.attributes.name_template'),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Myテンプレート名が未入力です',
        ];
    }
}
