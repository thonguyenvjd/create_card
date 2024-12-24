<?php

namespace App\Exceptions;

use Flugg\Responder\Exceptions\Http\HttpException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait HandleErrorException
{
    /**
     * @param \Throwable $th
     * @param $code - force response status
     * @param array $customData
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function processException(\Throwable $th, $code = null, array $customData = []): JsonResponse
    {
        if (empty($code)) {
            $code = $th->getCode();
        }

        $data = [
            'code'    => $code,
            'message' => __("api.msg_err_{$code}"),
        ];

        if (! App::isProduction()) {
            $data['detail'] = $th->getMessage();

            // if with=trace given, return trace
            $with = request('with');
            if (
                (is_string($with) && \Str::contains($with, 'trace'))
                || (is_array($with) && in_array('trace', $with))
            ) {
                $data['trace'] = $th->getTraceAsString();
            }
        }

        return response()->json(array_merge($data, $customData), $code);
    }

    public function renderApiServerErrorException(HttpException $exception): JsonResponse
    {
        return $this->processException($exception, Response::HTTP_INTERNAL_SERVER_ERROR, [
            'code' => ! empty($exception->getCode()) ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR,
        ]);
    }

    /**
     * @param \Illuminate\Validation\ValidationException $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderApiValidationResponse(ValidationException $exception): JsonResponse
    {
        return response()->json([
            'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => trans('api.msg_err_422'),
            'errors'  => $this->convertApiErrors($exception->errors()),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param $errors
     *
     * @return array
     */
    private function convertApiErrors($errors)
    {
        $result = [];
        foreach ($errors as $k => $error) {
            $result[] = [
                'field'   => $k,
                'message' => $error,
            ];
        }

        return $result;
    }

    public function renderApiNotFoundResponse(NotFoundHttpException $exception): JsonResponse
    {
        return $this->processException($exception, Response::HTTP_NOT_FOUND);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderNotLoginException(): JsonResponse
    {
        return response()->json([
            'code'    => Response::HTTP_NOT_FOUND,
            'message' => trans('api.msg_err_404'),
            'detail'  => trans('api.msg_err_404'),
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Exception\BadRequestHttpException $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderApiBadRequestResponse(BadRequestHttpException $exception): JsonResponse
    {
        return $this->processException($exception, Response::HTTP_BAD_REQUEST, [
            'code' => ! empty($exception->getCode()) ? $exception->getCode() : Response::HTTP_BAD_REQUEST,
        ]);
    }

    /**
     * Response server error exception
     *
     * @param $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderServerErrorException($exception): JsonResponse
    {
        return $this->processException($exception, Response::HTTP_INTERNAL_SERVER_ERROR, [
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        ]);
    }

    /**
     * @param $exception
     *
     * @return JsonResponse
     */
    public function renderForbiddenException($exception): JsonResponse
    {
        return $this->processException($exception, Response::HTTP_FORBIDDEN, [
            'code' => Response::HTTP_FORBIDDEN,
        ]);
    }

    /**
     * Response unauthenticated
     *
     * @param $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderUnauthenticatedException($exception): JsonResponse
    {
        return $this->processException($exception, Response::HTTP_UNAUTHORIZED, [
            'code' => Response::HTTP_UNAUTHORIZED,
        ]);
    }

    /**
     * Response access denied
     *
     * @param $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderAccessDeniedException($exception): JsonResponse
    {
        return $this->processException($exception, Response::HTTP_INTERNAL_SERVER_ERROR, [
            'code'        => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'     => trans('api.msg_err_500'),
            'url_return'  => '/login'
        ]);
    }
}
