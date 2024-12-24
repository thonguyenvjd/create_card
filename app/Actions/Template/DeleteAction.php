<?php

namespace App\Actions\Template;

use App\Supports\Traits\HasPerPageRequest;
use Illuminate\Http\JsonResponse;

class DeleteAction extends BaseAction
{
    use HasPerPageRequest;

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return JsonResponse
     */
    public function __invoke($id): JsonResponse
    {
        return \DB::transaction(function () use ($id) {
            /**
             * @var Template $template
             */
            if (strpos($id, ',') !== false) {
                $arrId = explode(',', $id);
                foreach ($arrId as $ids) {  
                    $template = $this->templateRepository->find($ids);

                    $template->delete();
                }
            } else {
                $template = $this->templateRepository->find($id);

                $template->delete();
            }

            return $this->httpNoContent();
        });
    }
}
