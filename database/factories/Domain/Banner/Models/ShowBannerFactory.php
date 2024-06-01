<?php

namespace Database\Factories\Domain\Banner\Models;

use App\Domain\Banner\Models\ShowBanner;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShowBannerFactory extends Factory
{
    protected $model = ShowBanner::class;

    public function definition()
    {
        return [
            'banner_id' => \App\Domain\Banner\Models\Banner::factory(),
            'user_id' => 1,
            'date_shown' => now(),
        ];
    }
}
