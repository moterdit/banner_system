<?php

namespace App\Domain\Banner\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $image_url Ссылка на изображение
 * @property string $redirect_url Ссылка для перехода по баннеру
 * @property boolean $is_active Статус активности
 * @property string $name Название баннера
 *
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 */
class Banner extends Model
{
    use HasFactory;
    protected $table = 'banner';
    protected $fillable = [
        'image_url',
        'redirect_url',
        'is_active',
        'name',
    ];
}