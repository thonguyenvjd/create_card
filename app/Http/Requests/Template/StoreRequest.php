<?php

namespace App\Http\Requests\Template;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'content'           => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'content'           => __('messages.attributes.content'),
        ];
    }

    public function messages(): array
    {
        return [
            'content.required'    => 'Myテンプレート内容が未入力です',
        ];
    }
}
