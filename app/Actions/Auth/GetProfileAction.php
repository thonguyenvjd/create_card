<?php

namespace App\Actions\Auth;

use App\Supports\Traits\HasTransformer;
use App\Transformers\UserTransformer;

class GetProfileAction
{
    use HasTransformer;

    public function __invoke()
    {
        $currentUser = auth()->user();
  
        return $this->httpOK($currentUser, UserTransformer::class);
    }
}
