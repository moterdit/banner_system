<?php

namespace App\Http\ApiV1\Modules\Banners\Controllers;

use App\Domain\Banner\Actions\CreateBannerAction;
use App\Domain\Banner\Actions\DeleteBannerAction;
use App\Domain\Banner\Actions\UpdateBannerAction;
use App\Domain\Banner\Actions\ShowBannerAction;
use App\Domain\Banner\Models\Banner;
use App\Domain\Banner\Models\ShowBanner;
use App\Http\ApiV1\Modules\Banners\Requests\CreateBannerRequest;
use App\Http\ApiV1\Modules\Banners\Requests\UpdateBannerRequest;
use App\Http\ApiV1\Modules\Banners\Resources\BannerResource;
use App\Http\ApiV1\Modules\Banners\Resources\ShowBannerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    public function index(Request $request)
    {
        Log::info('Executing index method in BannersController');
        $banners = Banner::all();
        return BannerResource::collection($banners);
    }

    public function show(int $id)
    {
        Log::info('Executing show method in BannersController', ['id' => $id]);
        $banner = Banner::findOrFail($id);
        return new BannerResource($banner);
    }

    public function store(CreateBannerRequest $request, CreateBannerAction $action)
    {
        Log::info('Executing store method in BannersController', ['request' => $request->all()]);
        $validated = $request->validated();
        $banner = $action->execute($validated);

        return new BannerResource($banner);
    }

    public function update(int $id, UpdateBannerRequest $request, UpdateBannerAction $action): BannerResource
    {
        Log::info('Executing update method in BannersController', ['id' => $id, 'request' => $request->all()]);
        $banner = Banner::findOrFail($id);
        $updatedBanner = $action->execute($banner, $request->validated());

        return new BannerResource($updatedBanner);
    }

    public function destroy(int $id, DeleteBannerAction $action): JsonResponse
    {
        Log::info('Executing destroy method in BannersController', ['id' => $id]);
        $banner = Banner::findOrFail($id);
        $action->execute($banner);

        return response()->json(null, 204);
    }

    public function showBanner(int $id, Request $request, ShowBannerAction $action): ShowBannerResource
    {
        Log::info('Executing showBanner method in BannersController', ['id' => $id]);
        
        // Получаем user_id из токена
        $user = $request->attributes->get('user');
        $userId = $user->user_id;

        $showBanner = $action->execute($id, $userId);

        return new ShowBannerResource($showBanner);
    }

    public function showBannerViews(Request $request)
    {
        Log::info('Executing showBannerViews method in BannersController');
        
        // Получаем user_id из токена
        $user = $request->attributes->get('user');
        $userId = $user->user_id;

        // Проверяем роль пользователя
        if ($user->role !== 'banner_owner') {
            Log::error('Unauthorized access to showBannerViews', ['user_id' => $userId]);
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Получаем все записи показа баннеров
        $bannerViews = ShowBanner::all();

        return ShowBannerResource::collection($bannerViews);
    }
}
