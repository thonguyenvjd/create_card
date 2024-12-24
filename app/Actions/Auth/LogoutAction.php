<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class LogoutAction extends BaseAction
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        /**
         * @var User $currentUser
         */
        $currentUser = auth()->user();

        // Revoke current token
        $currentUser->currentAccessToken()->delete();

        return $this->httpOK();
    }
}
