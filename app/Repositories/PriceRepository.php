<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class PriceRepository
{
    public function hasProductsWithPrices(int $categoryId, int $days = 7): bool
    {
        $since = now()->subDays($days)->toDateString();

        return DB::table('price as pr')
            ->join('product as p', 'p.product_id', '=', 'pr.product_id')
            ->where('p.category_id', $categoryId)
            ->where('pr.price_date', '>=', $since)
            ->exists();
    }

    public function getMinMaxPricesByCategory(int $categoryId, int $days = 7): LazyCollection
    {
        $since = now()->subDays($days)->toDateString();

        $ranked = DB::table('price as pr')
            ->join('product as p', 'p.product_id', '=', 'pr.product_id')
            ->join('manufacturer as m', 'm.manufacturer_id', '=', 'p.manufacturer_id')
            ->select([
                'm.manufacturer_name',
                'p.product_name',
                DB::raw('ROUND(pr.price::numeric, 2) as price'),
                'pr.price_date',
                DB::raw('ROW_NUMBER() OVER (PARTITION BY p.product_id ORDER BY pr.price ASC,  pr.price_date DESC) as rn_min'),
                DB::raw('ROW_NUMBER() OVER (PARTITION BY p.product_id ORDER BY pr.price DESC, pr.price_date DESC) as rn_max'),
            ])
            ->where('p.category_id', $categoryId)
            ->where('pr.price_date', '>=', $since);

        return DB::query()
            ->fromSub($ranked, 'ranked')
            ->select(['manufacturer_name', 'product_name', 'price', 'price_date'])
            ->whereRaw('rn_min = 1 OR rn_max = 1')
            ->orderBy('manufacturer_name')
            ->orderBy('product_name')
            ->cursor();
    }
}
