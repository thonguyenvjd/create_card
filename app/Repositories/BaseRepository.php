<?php

namespace App\Repositories;

use App\Supports\Traits\HasPerPageRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Exceptions\RepositoryException;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseRepository extends \Prettus\Repository\Eloquent\BaseRepository implements \App\Contracts\Repositories\BaseRepositoryInterface
{
    use HasPerPageRequest;

    protected string $defaultSort = '-created_at';

    protected array $allowedFilters = [];

    protected array $allowedSorts = [];

    protected array $allowedIncludes = [];

    protected array $allowedFields = ['*'];

    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @throws RepositoryException
     *
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $results = $this->model->get($columns);

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }

    /**
     * @param $limit
     * @param array $columns
     * @param string $method
     *
     * @throws RepositoryException
     *
     * @return LengthAwarePaginator|Collection|mixed
     */
    public function paginateOrAll($limit = null, array $columns = ['*'], string $method = 'paginate')
    {
        if ($columns === null) {
            $columns = ['*'];
        }
        if (! empty($limit)) {
            return $this->paginate($limit, $columns, $method);
        }

        return $this->all($columns);
    }

    public function addFilters($filter)
    {
        $this->allowedFilters = array_merge($this->allowedFilters, $filter);
    }

    public function addSorts($sorts)
    {
        $this->allowedSorts = array_merge($this->allowedSorts, $sorts);
    }
    
    /**
     * @throws RepositoryException
     *
     * @return $this
     */
    public function queryBuilder(): self
    {
        $model = $this->makeModel();
        $this->model = QueryBuilder::for($model)
            ->select(['*'])
            ->allowedFilters($this->allowedFilters)
            ->allowedFields($this->allowedFields)
            ->allowedIncludes($this->allowedIncludes)
            ->allowedSorts($this->allowedSorts);

        if (! empty($this->defaultSort)) {
            $this->model->defaultSort($this->defaultSort);
        }

        return $this;
    }

    /**
     * @param array $with
     *
     * @throws RepositoryException
     *
     * @return Collection
     */
    public function exportQuery(array $with = []): Collection
    {
        return $this->queryBuilder()
            ->with($with)
            ->when(! empty(request('per_page')), function ($query) {
                return $query->take($this->getPerPage())
                    ->offset($this->getOffset());
            })->get();
    }
}
