<?php

namespace App\Domain\Banner\Models;

use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $banner_id id баннера
 * @property CarbonInterface|null $date_shown Дата показа баннера
 * @property int $user_id id пользователя
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 */
class ShowBanner extends Model
{
    use HasFactory;
    protected $table = 'show_banner';
    protected $fillable = [
        'banner_id',
        'date_shown',
        'user_id',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format(DateTimeInterface::ATOM); // ISO-8601 format
    }

    public function banner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        // Связь с моделью Category
        return $this->belongsTo(Banner::class);
    }
}