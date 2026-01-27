<?php

namespace App\Traits;

use App\Http\Resources\ApiResponseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponseTrait
{
    protected function ok($data, string $message = ''): ApiResponseResource
    {
        return new ApiResponseResource($data, $message, true);
    }

    protected function okNoContent(): Response
    {
        return response()->noContent();
    }

    protected function created($data, string $message = ''): JsonResponse
    {
        return (new ApiResponseResource($data, $message, true))
            ->response()
            ->setStatusCode(201)
        ;
    }

    protected function error(string $message, array $errors = [], int $status = 400): JsonResponse
    {
        return (new ApiResponseResource(
            null,
            $message,
            false,
            null,
            $errors
        ))->response()->setStatusCode($status);
    }
}
