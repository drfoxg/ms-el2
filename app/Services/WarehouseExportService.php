<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Сервис экспорта товаров склада в CSV.
 *
 * Бизнес-логика вынесена в сервис, чтобы использовать из:
 * - Web-контроллера (диалог с прогрессом)
 * - API-контроллера (будущий эндпоинт)
 * - Artisan-команды
 *
 * Прогресс хранится в кэше (Redis/file/database — зависит от CACHE_DRIVER).
 */
class WarehouseExportService
{
    /** Префикс ключей кэша */
    private const CACHE_PREFIX = 'warehouse_export_';

    /** Время жизни ключей кэша в секундах */
    private const CACHE_TTL = 300;

    /** Размер пачки для обновления прогресса */
    private const BATCH_SIZE = 5000;

    /** Диск для хранения экспортов */
    private const STORAGE_DISK = 'public';

    /** Папка внутри диска */
    private const EXPORT_DIR = 'exports';

    // ──────────────────────────────────────────────
    //  Публичный API
    // ──────────────────────────────────────────────

    /**
     * Создать новый экспорт и вернуть его ID.
     */
    public function initExport(): string
    {
        $exportId = (string) Str::uuid();

        Cache::put($this->key($exportId, 'status'), 'pending', self::CACHE_TTL);
        Cache::put($this->key($exportId, 'progress'), 0, self::CACHE_TTL);
        Cache::put($this->key($exportId, 'total'), 0, self::CACHE_TTL);

        return $exportId;
    }

    /**
     * Выполнить экспорт (вызывается из Job).
     */
    public function runExport(string $exportId): void
    {
        Cache::put($this->key($exportId, 'status'), 'processing', self::CACHE_TTL);

        $totalRows = DB::table('warehouses')->count();
        $totalRows = max($totalRows, 1);

        Cache::put($this->key($exportId, 'total'), $totalRows, self::CACHE_TTL);
        Cache::put($this->key($exportId, 'progress'), 0, self::CACHE_TTL);

        // Гарантируем наличие директории
        Storage::disk(self::STORAGE_DISK)->makeDirectory(self::EXPORT_DIR);

        $filename = 'warehouses_' . $exportId . '.csv';
        $relativePath = self::EXPORT_DIR . '/' . $filename;
        $absolutePath = Storage::disk(self::STORAGE_DISK)->path($relativePath);

        $handle = fopen($absolutePath, 'w');
        // BOM для корректного открытия в Excel
        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
            'ID',
            'Парт-номер',
            'Название',
            'Цена',
            'В наличии',
            'Количество',
            'Рейтинг',
            'Производитель',
            'Поставщик',
            'Комментарий',
            'Создан',
        ], ';');

        $processed = 0;

        // Используем chunk для экономии памяти
        DB::table('warehouses')
            ->leftJoin('manufacturers', 'warehouses.manufacturer_id', '=', 'manufacturers.id')
            ->leftJoin('vendors', 'warehouses.vendor_id', '=', 'vendors.id')
            ->select([
                'warehouses.id',
                'warehouses.part_number',
                'warehouses.name',
                'warehouses.price',
                'warehouses.in_stock',
                'warehouses.stock_quantity',
                'warehouses.rating',
                'manufacturers.name as manufacturer_name',
                'vendors.name as vendor_name',
                'warehouses.comment',
                'warehouses.created_at',
            ])
            ->orderBy('warehouses.id')
            ->chunk(self::BATCH_SIZE, function ($rows) use ($handle, &$processed, $exportId, $totalRows) {
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->id,
                        $row->part_number,
                        $row->name,
                        $row->price,
                        $row->in_stock ? 'Да' : 'Нет',
                        $row->stock_quantity,
                        $row->rating,
                        $row->manufacturer_name ?? '',
                        $row->vendor_name ?? '',
                        $row->comment ?? '',
                        $row->created_at,
                    ], ';');

                    $processed++;
                }

                // Логируем память после каждого чанка
                Log::debug('Export memory: ' . round(memory_get_usage(true) / 1024 / 1024, 1) . ' MB');

                // Обновляем прогресс после каждой пачки
                Cache::put($this->key($exportId, 'progress'), $processed, self::CACHE_TTL);
            });

        fclose($handle);

        // Финализация
        $downloadUrl = Storage::disk(self::STORAGE_DISK)->url($relativePath);

        Cache::put($this->key($exportId, 'progress'), $totalRows, self::CACHE_TTL);
        Cache::put($this->key($exportId, 'file'), $downloadUrl, self::CACHE_TTL);
        Cache::put($this->key($exportId, 'status'), 'ready', self::CACHE_TTL);
    }

    /**
     * Получить текущий прогресс экспорта.
     *
     * @return array{percent: float, ready: bool, download_url: string|null, status: string}
     */
    public function getProgress(string $exportId): array
    {
        $status   = Cache::get($this->key($exportId, 'status'), 'unknown');
        $progress = (int) Cache::get($this->key($exportId, 'progress'), 0);
        $total    = (int) Cache::get($this->key($exportId, 'total'), 0);
        $file     = Cache::get($this->key($exportId, 'file'));

        $percent = $total > 0
            ? round($progress / $total * 100, 1)
            : 0;

        $ready = $status === 'ready';

        return [
            'percent'      => $percent,
            'ready'        => $ready,
            'download_url' => $ready ? $file : null,
            'status'       => $status,
        ];
    }

    // ──────────────────────────────────────────────
    //  Приватные методы
    // ──────────────────────────────────────────────

    private function key(string $exportId, string $suffix): string
    {
        return self::CACHE_PREFIX . $exportId . ':' . $suffix;
    }
}
