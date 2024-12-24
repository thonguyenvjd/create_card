<?php

namespace App\Actions\Template;

use App\Supports\Traits\HasPerPageRequest;
use App\Transformers\TemplateTransformer;
use Illuminate\Http\JsonResponse;

class UpdateAction extends BaseAction
{
    use HasPerPageRequest;

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return JsonResponse
     */
    public function __invoke(int $id, array $data): JsonResponse
    {
        return \DB::transaction(function () use ($id, $data) {
            /**
             * @var Template $template
             */
            $template = $this->templateRepository->update($data, $id);

            return $this->httpOK($template, TemplateTransformer::class);
        });
    }
}
