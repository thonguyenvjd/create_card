<?php

namespace App\Http\Controllers;

use App\Actions\Auth\GetProfileAction;
use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\RegisterAction;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    
    /**
     * @param LoginRequest $request
     * @param LoginAction $action
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        return ($action)($request->validated());
    }

    /**
     * @param LogoutAction $action
     *
     * @return JsonResponse
     */
    public function logout(LogoutAction $action): JsonResponse
    {
        return ($action)();
    }

    /**
     * @param GetProfileAction $action
     *
     * @return SuccessResponseBuilder|JsonResponse
     */
    public function me(GetProfileAction $action): JsonResponse
    {
        return ($action)();
    }

    /**
     * @param RegisterRequest $request
     * @param RegisterAction $action
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        return ($action)($request->validated());
    }
}
