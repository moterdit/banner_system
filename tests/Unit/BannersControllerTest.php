<?php

namespace Tests\Unit;

use App\Domain\Banner\Models\Banner;
use App\Domain\Banner\Models\ShowBanner;
use App\Http\ApiV1\Modules\Banners\Controllers\BannersController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Tests\TestCase;

class BannersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShowBannerViews()
    {
        // Создаем пользователя с ролью banner_owner
        $user = (object) ['user_id' => 1, 'role' => 'banner_owner'];

        // Создаем баннер
        $banner = Banner::factory()->create();

        // Создаем тестовые данные для показов баннера
        ShowBanner::factory()->create([
            'banner_id' => $banner->id,
            'user_id' => 2,
            'date_shown' => now(),
        ]);

        // Создаем запрос и добавляем атрибуты
        $request = Request::create('/banners/views', 'GET');
        $request->attributes->set('user', $user);

        // Устанавливаем атрибуты запроса вручную
        $this->app['request'] = $request;

        // Тестируемый контроллер
        $controller = new BannersController();

        // Вызов метода
        $response = $controller->showBannerViews($request);

        // Проверка результатов
        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertCount(1, $response->collection);
    }
}
