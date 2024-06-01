<?php

namespace App\Domain\Banner\Actions;

use App\Domain\Banner\Models\ShowBanner;
use Illuminate\Support\Facades\Log;

class ShowBannerAction
{
    public function execute(int $bannerId, int $userId): ShowBanner
    {
        $showBanner = new ShowBanner();
        $showBanner->banner_id = $bannerId;
        $showBanner->user_id = $userId; // Использование user_id из запроса
        $showBanner->date_shown = now();
        $showBanner->save();

        Log::info('Banner shown recorded', ['banner_id' => $bannerId, 'user_id' => $userId]);

        return $showBanner;
    }
}
