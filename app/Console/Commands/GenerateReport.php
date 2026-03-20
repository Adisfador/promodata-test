<?php

namespace App\Console\Commands;

use App\Services\ReportService;
use Illuminate\Console\Command;

class GenerateReport extends Command
{
    protected $signature   = 'report:generate {category_id : ID категории товаров}';
    protected $description = 'Генерирует CSV-отчёт по минимальным и максимальным ценам товаров категории за 7 дней';

    public function handle(ReportService $reportService): int
    {
        $categoryId = (int) $this->argument('category_id');

        if ($categoryId <= 0) {
            $this->error('category_id должен быть положительным числом.');
            return self::FAILURE;
        }

        $this->info("Генерация отчёта для категории {$categoryId}...");

        try {
            $filePath = $reportService->generate($categoryId);
            $this->info("Отчёт успешно сохранён: {$filePath}");
            return self::SUCCESS;
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
