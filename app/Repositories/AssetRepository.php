<?php

namespace App\Repositories;

use App\Models\Asset;

class AssetRepository extends BaseRepository implements \App\Contracts\Repositories\AssetRepositoryInterface
{
    protected array $allowedFilters = [];

    public function boot(): void
    {
        parent::boot();
    }

    public function model(): string
    {
        return Asset::class;
    }
}
