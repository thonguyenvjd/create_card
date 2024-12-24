<?php

namespace App\Actions\Auth;

use App\Repositories\UserRepository;
use App\Supports\Traits\HasTransformer;

abstract class BaseAction
{
    use HasTransformer;

    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}
