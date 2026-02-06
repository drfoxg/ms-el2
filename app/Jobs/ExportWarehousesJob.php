<?php

namespace App\Jobs;

use App\Services\WarehouseExportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Джоб экспорта товаров склада в CSV.
 *
 * Запускается через очередь (Redis, database, sync — зависит от QUEUE_CONNECTION).
 * Если QUEUE_CONNECTION=sync, выполнится синхронно (подходит для dev-окружения).
 */
class ExportWarehousesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** Макс. количество попыток */
    public int $tries = 3;

    /** Таймаут в секундах */
    public int $timeout = 300;

    public function __construct(
        public readonly string $exportId,
    ) {
    }

    public function handle(WarehouseExportService $service): void
    {
        try {
            $service->runExport($this->exportId);
        } catch (\Throwable $e) {
            Log::error("Warehouse export failed [{$this->exportId}]: " . $e->getMessage());
            throw $e;
        }
    }
}
