<?php

namespace App\Actions\Template;

use App\Supports\Traits\HasPerPageRequest;
use App\Transformers\ExportCardJobTransform;
use Illuminate\Http\JsonResponse;

class JobsAction extends BaseAction
{
    use HasPerPageRequest;

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $jobs = $this->exportCardJobRepository->queryBuilder()->where('user_id', auth()->user()->id)->paginate(10);

        return $this->httpOK($jobs, ExportCardJobTransform::class);
    }
}
