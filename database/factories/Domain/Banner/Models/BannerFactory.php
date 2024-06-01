<?php

namespace Database\Factories\Domain\Banner\Models;

use App\Domain\Banner\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition()
    {
        return [
            'image_url' => $this->faker->url,
            'redirect_url' => $this->faker->url,
            'is_active' => $this->faker->boolean,
            'name' => $this->faker->word,
        ];
    }
}
