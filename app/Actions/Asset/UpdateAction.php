<?php

namespace App\Actions\Asset;

use App\Supports\Traits\HasPerPageRequest;
use App\Transformers\AssetTransformer;
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
            if (isset($data['file'])) {
                $uploadResult = $this->s3Service->upload($data['file'], 'assets');
                
                if (!$uploadResult['success']) {
                    return response()->json([
                        'message' => 'Upload failed: ' . $uploadResult['message']
                    ], 400);
                }

                $data['src'] = $uploadResult['url'];
                $data['name'] = $data['file']->getClientOriginalName();
                $data['type'] = 'IMAGE';
            }

            $asset = $this->assetRepository->update($data, $id);

            return $this->httpOK($asset, AssetTransformer::class);
        });
    }
}
