<?php

namespace App\Actions\Template;

use App\Repositories\ExportCardJobRepository;
use App\Repositories\TemplateRepository;
use App\Supports\Traits\HasTransformer;

abstract class BaseAction
{
    use HasTransformer;

    protected TemplateRepository $templateRepository;
    protected ExportCardJobRepository $exportCardJobRepository;

    public function __construct(TemplateRepository $templateRepository, ExportCardJobRepository $exportCardJobRepository)
    {
        $this->templateRepository = $templateRepository;
        $this->exportCardJobRepository = $exportCardJobRepository;
    }
}
