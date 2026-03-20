<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('process_status')->insertOrIgnore([
            ['ps_id' => 1, 'ps_name' => 'Запуск'],
            ['ps_id' => 2, 'ps_name' => 'Завершен'],
            ['ps_id' => 3, 'ps_name' => 'Ошибка'],
        ]);

        DB::table('price')->truncate();
        DB::table('product')->truncate();
        DB::table('manufacturer')->truncate();

        Manufacturer::factory(50)->create();

        $this->command->info('Создаём товары...');
        Product::factory(5000)->create();

        $this->command->info('Создаём цены (1 000 000 записей)...');
        $productIds = DB::table('product')->pluck('product_id')->toArray();
        $chunkSize  = 1000;
        $total      = 1000000;

        $rows = [];
        for ($i = 0; $i < $total; $i++) {
            $rows[] = [
                'product_id' => $productIds[array_rand($productIds)],
                'price'      => round(mt_rand(1000, 9999999) / 100, 2),
                'price_date' => now()->subDays(mt_rand(0, 30))->format('Y-m-d'),
            ];

            if (count($rows) === $chunkSize) {
                DB::table('price')->insert($rows);
                $rows = [];
            }
        }

        if (!empty($rows)) {
            DB::table('price')->insert($rows);
        }

        $this->command->info('Готово!');
    }
}
