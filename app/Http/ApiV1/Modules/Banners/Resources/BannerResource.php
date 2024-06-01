<?php

namespace App\Http\ApiV1\Modules\Banners\Resources;

use App\Domain\Banner\Models\Banner;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin Banner
 */
class BannerResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'image_url' => $this->image_url,
            'redirect_url' => $this->redirect_url,
            'is_active' => $this->is_active,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
