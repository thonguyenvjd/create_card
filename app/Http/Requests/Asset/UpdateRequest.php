<?php

namespace App\Http\Requests\Asset;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'file'      => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:10240',
        ];
    }
}
