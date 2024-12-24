<?php

namespace App\Actions\Template;

use App\Supports\Traits\HasPerPageRequest;
use App\Transformers\TemplateTransformer;
use Illuminate\Http\JsonResponse;

class IndexAction extends BaseAction
{
    use HasPerPageRequest;

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $templates = $this->templateRepository->queryBuilder()->where('user_id', auth()->user()->id)->paginate(10);

        return $this->httpOK($templates, TemplateTransformer::class);
    }
}
