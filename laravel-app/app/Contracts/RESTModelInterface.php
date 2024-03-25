<?php
declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface RESTModelInterface
{
    /**
     * Oldalanként max mennyi elem legyen
     * @return int
     */
    public static function getPageLimit(): int;

    /**
     * A query előkészítése (relation-ök, relation-ök mezői stb specifikus részek)
     * @return Builder
     */
    public static function getItemsQuery(): Builder;

}
