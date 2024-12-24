<?php

namespace App\Repositories;

use App\Models\Template;
use Spatie\QueryBuilder\AllowedFilter;

class TemplateRepository extends BaseRepository implements \App\Contracts\Repositories\TemplateRepositoryInterface
{
    protected array $allowedFilters = [
        'name',
    ];

    public function boot(): void
    {
        parent::boot();
        $this->addFilters([
            AllowedFilter::exact('type'),
        ]);
    }

    public function model(): string
    {
        return Template::class;
    }
}
