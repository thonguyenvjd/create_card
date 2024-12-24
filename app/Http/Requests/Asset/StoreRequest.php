<?php

namespace App\Http\Requests\Asset;

use App\Http\Requests\BaseRequest;

class StoreRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'file'      => 'required|image|mimes:jpg,jpeg,png,gif|max:10240',
        ];
    }
}
