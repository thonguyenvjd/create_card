<?php

namespace App\Actions\Auth;

use App\Transformers\TokenTransformer;
use Hash;
use Illuminate\Http\JsonResponse;

class LoginAction extends BaseAction
{
    /**
     * @param array $data
     *
     * @return JsonResponse
     */
    public function __invoke(array $data): JsonResponse
    {
        if (($user = $this->userRepository->whereEmail($data['email'])->first())
            && Hash::check($data['password'], $user->password)) {
        
            $token = $user->createToken('Login', ['*']);

            return $this->httpOK($token, TokenTransformer::class);
        }
        
        return $this->httpUnauthorized();
    }
}
