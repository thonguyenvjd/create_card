<?php

namespace App\Repositories;

use App\Models\ExportCardJob;

class ExportCardJobRepository extends BaseRepository implements \App\Contracts\Repositories\UserRepositoryInterface
{
    protected array $allowedFilters = [];

    public function boot(): void
    {
        parent::boot();
    }

    public function model(): string
    {
        return ExportCardJob::class;
    }
}
