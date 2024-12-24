<?php

namespace App\Actions\Template;

use App\Repositories\TemplateRepository;
use App\Supports\Traits\HasTransformer;

abstract class BaseAction
{
    use HasTransformer;

    protected TemplateRepository $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }
}
