<?php

namespace App\Services;

use App\Enums\ProcessStatus;
use App\Repositories\PriceRepository;
use App\Repositories\ReportProcessRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function __construct(
        private readonly PriceRepository $priceRepository,
        private readonly ReportProcessRepository $reportProcessRepository,
    ) {
    }

    public function generate(int $categoryId): string
    {
        $startMs = hrtime(true);

        $process = $this->reportProcessRepository->create([
            'rp_pid'            => getmypid(),
            'rp_start_datetime' => now(),
            'rp_exec_time'      => null,
            'ps_id'             => ProcessStatus::Running,
            'rp_file_save_path' => null,
        ]);

        try {
            if (!$this->priceRepository->hasProductsWithPrices($categoryId)) {
                throw new \RuntimeException(
                    "Для категории {$categoryId} не найдено товаров с ценами за последние 7 дней."
                );
            }

            $rows     = $this->priceRepository->getMinMaxPricesByCategory($categoryId);
            $filePath = $this->writeCsv($categoryId, $rows);

            $process->update([
                'ps_id'             => ProcessStatus::Finished,
                'rp_exec_time'      => (int) ((hrtime(true) - $startMs) / 1e6),
                'rp_file_save_path' => $filePath,
            ]);

            return $filePath;
        } catch (\Throwable $e) {
            Log::error('ReportService: ' . $e->getMessage(), [
                'category_id' => $categoryId,
                'exception'   => $e,
            ]);

            $process->update([
                'ps_id'        => ProcessStatus::Error,
                'rp_exec_time' => (int) ((hrtime(true) - $startMs) / 1e6),
            ]);

            throw $e;
        }
    }

    private function writeCsv(int $categoryId, iterable $rows): string
    {
        $relativePath = sprintf('reports/report_%d_%s.csv', $categoryId, now()->format('Y-m-d_H-i-s'));

        Storage::makeDirectory('reports');

        $file = new \SplFileObject(Storage::path($relativePath), 'w');

        $file->fwrite("\u{FEFF}");
        $file->fputcsv(['manufacturer_name', 'product_name', 'price', 'price_date'], ';');

        foreach ($rows as $row) {
            $file->fputcsv([
                $row->manufacturer_name,
                $row->product_name,
                $row->price,
                $row->price_date,
            ], ';');
        }

        $file->fflush();
        unset($file);

        return $relativePath;
    }
}
