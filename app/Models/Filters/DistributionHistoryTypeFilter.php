<?php

namespace App\Models\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class DistributionHistoryTypeFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        if (is_array($value)) {
            $query->whereHas('reservation', function ($query) use ($value) {
                $query->whereIn('email_type', $value);
            });
        }

        return $query;
    }
}
