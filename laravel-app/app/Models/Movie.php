<?php
declare(strict_types=1);

namespace App\Models;

use App\Contracts\RESTModelInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model implements RESTModelInterface
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }

    /**
     * max mennyi elem menjen egyszerre
     * @return int
     */
    public static function getPageLimit(): int
    {
        return config('restapi.max_items_per_page');
    }

    /**
     * A query előkészítése (relation-ök, relation-ök mezői stb)
     * @return Builder
     */
    public static function getItemsQuery(): Builder
    {
        return self::query();
    }


}
