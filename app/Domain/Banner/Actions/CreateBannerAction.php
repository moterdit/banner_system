<?php

namespace App\Domain\Banner\Actions;

use App\Domain\Banner\Models\Banner;

class CreateBannerAction
{
    public function execute(array $fields): Banner
    {
        $banner = new Banner();
        $banner->fill($fields);
        $banner->save();

        return $banner;
    }
}
