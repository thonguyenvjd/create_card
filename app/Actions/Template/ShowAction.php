<?php

namespace App\Actions\Template;

use App\Models\Template;
use App\Supports\Traits\HasPerPageRequest;
use App\Transformers\TemplateTransformer;
use Illuminate\Http\JsonResponse;

class ShowAction extends BaseAction
{
    use HasPerPageRequest;

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
         /**
         * @var Template $template
         */
        $template = $this->templateRepository->find($id);

        return $this->httpOK($template, TemplateTransformer::class);
    }
}
