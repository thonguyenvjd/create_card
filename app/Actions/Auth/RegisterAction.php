<?php

namespace App\Actions\Auth;

use App\Supports\Traits\HasPerPageRequest;
use Illuminate\Http\JsonResponse;

class RegisterAction extends BaseAction
{
    use HasPerPageRequest;

    /**
     * @return JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function __invoke(array $data): JsonResponse
    {
        $this->userRepository->create($data);

        return $this->httpOK();
    }
}
