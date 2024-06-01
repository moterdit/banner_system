<?php

namespace App\Http\ApiV1\Modules\Banners\Requests;

use App\Http\ApiV1\Support\Requests\BaseFormRequest;

class CreateBannerRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'image_url' => ['required', 'string'],
            'redirect_url' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
            'name' => ['required', 'string'],
        ];
    }
}
