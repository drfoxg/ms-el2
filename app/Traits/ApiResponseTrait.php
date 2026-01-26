<?php

namespace App\Traits;

use App\Http\Resources\ApiResponseResource;

trait ApiResponseTrait
{
    protected function ok($data, string $message = '' /*, array $meta = []*/)
    {
        // return new ApiResponseResource($data, $message, true, $meta);
        return new ApiResponseResource($data, $message, true);
    }

    protected function okNoContent()
    {
        return response()->noContent();
    }

    protected function created($data, string $message = '')
    {
        return (new ApiResponseResource($data, $message, true))
            ->response()
            ->setStatusCode(201)
        ;
    }

    protected function error(
        string $message,
        array $errors = [],
        int $status = 400
    ) {
        return (new ApiResponseResource(
            null,
            $message,
            false,
            null,
            $errors
        ))->response()->setStatusCode($status);
    }
}
