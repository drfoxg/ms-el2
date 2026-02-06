<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanExportFiles extends Command
{
    protected $signature = 'exports:clean
        {path=exports : Папка внутри диска public (storage/app/public/...)}
        {format? : Формат файлов для удаления (csv, pdf, txt)}
        {--days=1 : Удалять файлы старше N дней}
        {--D|dry-run : Показать файлы без удаления}';

    protected $description = 'Удаление старых файлов экспорта';

    public function handle(): int
    {
        $disk    = Storage::disk('public');
        $path    = $this->argument('path');
        $format  = $this->argument('format') ?? 'csv';
        $days    = (int) $this->option('days');
        $dryRun  = $this->option('dry-run');

        if (!$disk->exists($path)) {
            $this->warn("Папка '{$path}' не найдена на диске public");
            return self::FAILURE;
        }

        $files   = $disk->files($path);
        $cutoff  = now()->subDays($days)->timestamp;
        $deleted = 0;
        $skipped = 0;

        foreach ($files as $file) {
            // Фильтр по формату
            if (!str_ends_with($file, '.' . $format)) {
                $skipped++;
                continue;
            }

            $lastModified = $disk->lastModified($file);

            if ($lastModified >= $cutoff) {
                $skipped++;
                continue;
            }

            $size = $this->formatSize($disk->size($file));
            $date = date('Y-m-d H:i', $lastModified);

            if ($dryRun) {
                $this->line("[dry-run] {$file}  ({$size}, {$date})");
            } else {
                $disk->delete($file);
                $this->line("Удалён: {$file}  ({$size}, {$date})");
            }

            $deleted++;
        }

        $action = $dryRun ? 'Найдено для удаления' : 'Удалено';
        $this->info("{$action}: {$deleted}, пропущено: {$skipped}");

        return self::SUCCESS;
    }

    private function formatSize(int $bytes): string
    {
        return match (true) {
            $bytes >= 1048576 => round($bytes / 1048576, 1) . ' MB',
            $bytes >= 1024    => round($bytes / 1024, 1) . ' KB',
            default           => $bytes . ' B',
        };
    }
}
