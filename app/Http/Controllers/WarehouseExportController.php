<?php

namespace App\Http\Controllers;

use App\Jobs\ExportWarehousesJob;
use App\Services\WarehouseExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Контроллер экспорта товаров склада.
 *
 * Два эндпоинта:
 * - POST   /warehouse/export          → запуск экспорта
 * - GET    /warehouse/export/{id}      → получение прогресса
 *
 * Доступно как авторизованным, так и неавторизованным пользователям.
 */
class WarehouseExportController extends Controller
{
    public function __construct(
        private readonly WarehouseExportService $exportService,
    ) {
    }

    /**
     * Запустить экспорт.
     *
     * POST /warehouse/export
     */
    public function start(Request $request): JsonResponse
    {
        $exportId = $this->exportService->initExport();

        ExportWarehousesJob::dispatch($exportId);

        return response()->json([
            'status'    => 'started',
            'export_id' => $exportId,
        ]);
    }

    /**
     * Получить прогресс экспорта.
     *
     * GET /warehouse/export/{exportId}
     */
    public function progress(string $exportId): JsonResponse
    {
        $data = $this->exportService->getProgress($exportId);

        return response()->json($data);
    }
}

