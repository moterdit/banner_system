<?php

namespace App\Domain\Banner\Actions;

use App\Domain\Banner\Models\Banner;

class DeleteBannerAction
{
    public function execute(Banner $banner): void
    {
        $banner->delete();
    }
}
