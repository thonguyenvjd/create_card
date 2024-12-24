<?php

namespace App\Exceptions;

use Flugg\Responder\Exceptions\ConvertsExceptions;
use Flugg\Responder\Exceptions\Http\HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Spatie\QueryBuilder\Exceptions\InvalidFilterQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler extends ExceptionHandler
{
    use ConvertsExceptions, HandleErrorException;

    protected $dontReport = [
        TenantCouldNotBeIdentifiedException::class,
    ];

    public function render($request, Throwable $e)
    {
        switch (true) {
            case $e instanceof HttpException:
                return $this->renderApiServerErrorException($e);
            case $e instanceof ConflictHttpException:
                return $this->renderConflictResponse($e);
            case $e instanceof ValidationException:
                return $this->renderApiValidationResponse($e);
            case $e instanceof NotFoundHttpException:
                return $this->renderApiNotFoundResponse($e);
            case $e instanceof ModelNotFoundException:
                return $this->renderApiModelNotFoundResponse($e);
            case $e instanceof BadRequestHttpException:
                return $this->renderApiBadRequestResponse($e);
            case $e instanceof MissingAbilityException:
            case $e instanceof AuthorizationException:
                return $this->renderForbiddenException($e);
            case $e instanceof AuthenticationException:
                return $this->renderUnauthenticatedException($e);
            case $e instanceof InvalidFilterQuery:
                return $this->renderInvalidFilter($e);
            case $e instanceof AccessDeniedHttpException:
                return $this->renderAccessDeniedException($e);
            default:
                return $this->renderServerErrorException($e);
        }
    }

    protected function renderApiModelNotFoundResponse(ModelNotFoundException $exception): JsonResponse
    {
        return $this->processException($exception, Response::HTTP_NOT_FOUND, [
            'code' => Response::HTTP_NOT_FOUND,
        ]);
    }

    private function renderInvalidFilter(InvalidFilterQuery $exception): JsonResponse
    {
        return $this->processException($exception, Response::HTTP_BAD_REQUEST, [
            'code'    => Response::HTTP_BAD_REQUEST,
            'message' => __('errors.filter_error'),
        ]);
    }

    private function renderConflictResponse(ConflictHttpException $exception): JsonResponse
    {
        return $this->processException($exception, Response::HTTP_CONFLICT, [
            'message' => $exception->getMessage(),
        ]);
    }
}
