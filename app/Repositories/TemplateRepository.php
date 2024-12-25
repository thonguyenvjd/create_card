<?php

namespace App\Repositories;

use App\Models\Template;
use Spatie\QueryBuilder\AllowedFilter;

class TemplateRepository extends BaseRepository implements \App\Contracts\Repositories\TemplateRepositoryInterface
{
    protected array $allowedFilters = [];

    public function boot(): void
    {
        parent::boot();
    }

    public function model(): string
    {
        return Template::class;
    }
}
