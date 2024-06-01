<?php

namespace App\Http\ApiV1\Modules\Banners\Requests;

use App\Http\ApiV1\Support\Requests\BaseFormRequest;

class UpdateBannerRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'image_url' => ['sometimes', 'string'],
            'redirect_url' => ['sometimes', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'name' => ['sometimes', 'string'],
        ];
    }
}
