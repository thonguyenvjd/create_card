<?php

namespace App\Actions\Asset;

use App\Repositories\AssetRepository;
use App\Services\S3Service;
use App\Supports\Traits\HasTransformer;

abstract class BaseAction
{
    use HasTransformer;

    protected AssetRepository $assetRepository;
    protected S3Service $s3Service;

    public function __construct(AssetRepository $assetRepository, S3Service $s3Service)
    {
        $this->assetRepository = $assetRepository;
        $this->s3Service = $s3Service;
    }
}
