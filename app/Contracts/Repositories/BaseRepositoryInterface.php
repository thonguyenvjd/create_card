<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Exceptions\RepositoryException;

interface BaseRepositoryInterface extends RepositoryInterface
{
    /**
     * @param $limit
     * @param array $columns
     * @param string $method
     *
     * @throws RepositoryException
     *
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function paginateOrAll($limit = null, array $columns = ['*'], string $method = 'paginate');

    /**
     * @return $this
     */
    public function queryBuilder(): self;

    /**
     * @param array $with
     *
     * @return Collection
     */
    public function exportQuery(array $with = []): Collection;
}
