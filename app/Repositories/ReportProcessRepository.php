<?php

namespace App\Repositories;

use App\Models\ReportProcess;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportProcessRepository
{
    public function create(array $data): ReportProcess
    {
        return ReportProcess::create($data);
    }

    public function paginate(int $perPage = 50): LengthAwarePaginator
    {
        return ReportProcess::with('status')
            ->orderByDesc('rp_start_datetime')
            ->paginate($perPage);
    }
}
