<?php

namespace App\Http\Requests\Template;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'content'           => 'required',
            'file'              => 'required|file|mimes:csv,txt',
            'user_id'           => 'required|exists:users,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'file'              => __('messages.attributes.file'),
        ];
    }

    public function messages(): array
    {
        return [
            'file.required'    => 'CSVファイルが未選択です',
        ];
    }
}
