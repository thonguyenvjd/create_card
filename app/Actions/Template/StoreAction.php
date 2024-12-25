<?php

namespace App\Actions\Template;

use App\Supports\Traits\HasPerPageRequest;
use App\Transformers\TemplateTransformer;
use Illuminate\Http\JsonResponse;

class StoreAction extends BaseAction
{
    use HasPerPageRequest;

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return JsonResponse
     */
    public function __invoke(array $data): JsonResponse
    {
        return \DB::transaction(function () use ($data) {
            /**
             * @var Template $template
             */
            $template = $this->templateRepository->create($data);

            return $this->httpOK($template, TemplateTransformer::class);
        });
    }
}
