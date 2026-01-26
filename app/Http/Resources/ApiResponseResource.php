<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ApiResponseResource extends JsonResource
{
    protected string $message;
    protected bool $success;
    protected ?array $errors;
    protected ?array $meta;
    protected ?array $links;

    public function __construct(
        mixed $resource = null,
        string $message = '',
        bool $success = true,
        ?array $errors = null
    ) {
        parent::__construct($resource);

        $this->message = $message;
        $this->success = $success;
        $this->errors = $errors;
        $this->meta = null;
        $this->links = null;

        // Обрабатываем пагинацию, если ресурс — это коллекция
        if ($resource instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection) {
            $inner = $resource->resource; // внутренний Paginator или Collection

            if ($inner instanceof LengthAwarePaginator) {
                $this->meta = [
                    'current_page' => $inner->currentPage(),
                    'per_page'     => $inner->perPage(),
                    'total'        => $inner->total(),
                    'last_page'    => $inner->lastPage(),
                ];

                $this->links = [
                    'first' => $inner->url(1),
                    'last'  => $inner->url($inner->lastPage()),
                    'prev'  => $inner->previousPageUrl(),
                    'next'  => $inner->nextPageUrl(),
                ];
            }
        }
    }

    public function toArray($request): array
    {
        return $this->success
            ? $this->successResponse()
            : $this->errorResponse();
    }

    protected function successResponse(): array
    {
        $data = $this->resource;

        // Разворачиваем ResourceCollection в массив
        if ($data instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection) {
            $data = $data->resolve();
        }

        return [
            'success' => true,
            'message' => $this->message,
            'data'    => $data,
            'meta'    => $this->meta,
            'links'   => $this->links,
        ];
    }

    protected function errorResponse(): array
    {
        return [
            'success' => false,
            'message' => $this->message,
            'errors'  => $this->errors ?? [],
        ];
    }
}
