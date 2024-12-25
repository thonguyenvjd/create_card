<?php

namespace App\Http\Requests\Template;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'content'           => 'sometimes',
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
