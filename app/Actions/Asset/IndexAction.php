<?php

namespace App\Actions\Asset;

use App\Supports\Traits\HasPerPageRequest;
use App\Transformers\AssetTransformer;
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
        $assets = $this->assetRepository->queryBuilder()->where('user_id', auth()->user()->id)->paginate(10);

        return $this->httpOK($assets, AssetTransformer::class);
    }
}
