<?php

namespace App\Http\ApiV1\Modules\Banners\Resources;

use App\Domain\Banner\Models\ShowBanner;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin ShowBanner
 */
class ShowBannerResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'banner_id' => $this->banner_id,
            'date_shown' => $this->date_shown,
            'user_id' => $this->user_id,
        ];
    }
}
