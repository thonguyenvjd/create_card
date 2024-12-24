<?php

namespace App\Actions\Asset;

use App\Transformers\AssetTransformer;
use Illuminate\Http\JsonResponse;

class StoreAction extends BaseAction
{
    public function __invoke(array $data): JsonResponse
    {
        return \DB::transaction(function () use ($data) {
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

            $asset = $this->assetRepository->create($data);

            return $this->httpOK($asset, AssetTransformer::class);
        });
    }
}
