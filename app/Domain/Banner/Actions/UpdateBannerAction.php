<?php

namespace App\Domain\Banner\Actions;

use App\Domain\Banner\Models\Banner;

class UpdateBannerAction
{
    public function execute(Banner $banner, array $fields): Banner
    {
        $banner->update($fields);
        return $banner;
    }
}
