<?php

namespace App\Actions\Asset;

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
             * @var Asset $asset
             */
            if (strpos($id, ',') !== false) {
                $arrId = explode(',', $id);
                foreach ($arrId as $ids) {  
                    $asset = $this->assetRepository->find($ids);

                    $asset->delete();
                }
            } else {
                $asset = $this->assetRepository->find($id);

                $asset->delete();
            }

            return $this->httpNoContent();
        });
    }
}
