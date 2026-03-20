<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контроль выполнения процессов</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 0; padding: 24px; background: #f5f5f5; color: #333; }
        h1 { margin-bottom: 20px; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 6px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
        thead { background: #3b5bdb; color: #fff; }
        th, td { padding: 10px 14px; text-align: left; white-space: nowrap; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        tbody tr:hover { background: #eef2ff; }
        tr.error-row { background: #ffe0e0 !important; color: #b00020; }
        tr.error-row:hover { background: #ffc9c9 !important; }
        a { color: #3b5bdb; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .pagination { margin-top: 16px; display: flex; gap: 6px; }
        .pagination a, .pagination span { padding: 6px 12px; border: 1px solid #ccc; border-radius: 4px; background: #fff; cursor: pointer; }
        .pagination span.active { background: #3b5bdb; color: #fff; border-color: #3b5bdb; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: bold; }
        .badge-running  { background: #fff3cd; color: #856404; }
        .badge-finished { background: #d1e7dd; color: #0f5132; }
        .badge-error    { background: #f8d7da; color: #842029; }
    </style>
</head>
<body>
    <h1>Контроль выполнения процессов</h1>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Дата запуска</th>
                <th>Время запуска</th>
                <th>Время выполнения</th>
                <th>PID</th>
                <th>Статус</th>
                <th>Файл</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($processes as $process)
                @php
                    $isError = $process->ps_id === \App\Enums\ProcessStatus::Error;
                    $statusName = $process->status?->ps_name ?? '—';
                    $badgeClass = match($process->ps_id) {
                        \App\Enums\ProcessStatus::Running  => 'badge-running',
                        \App\Enums\ProcessStatus::Finished => 'badge-finished',
                        \App\Enums\ProcessStatus::Error    => 'badge-error',
                        default => '',
                    };
                    $execMs = $process->rp_exec_time;
                    $execFormatted = $execMs !== null
                        ? ($execMs >= 1000 ? round($execMs / 1000, 2) . ' с' : $execMs . ' мс')
                        : '—';
                @endphp
                <tr @if($isError) class="error-row" @endif>
                    <td>{{ $process->rp_id }}</td>
                    <td>{{ $process->rp_start_datetime?->format('d.m.Y') }}</td>
                    <td>{{ $process->rp_start_datetime?->format('H:i:s') }}</td>
                    <td>{{ $execFormatted }}</td>
                    <td>{{ $process->rp_pid }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ $statusName }}</span></td>
                    <td>
                        @if ($process->ps_id === \App\Enums\ProcessStatus::Finished && $process->rp_file_save_path)
                            <a href="{{ route('reports.download', $process->rp_id) }}">
                                {{ basename($process->rp_file_save_path) }}
                            </a>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 24px; color: #888;">
                        Процессов пока нет. Запустите: <code>php artisan report:generate {category_id}</code>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($processes->hasPages())
        <div class="pagination">
            @if ($processes->onFirstPage())
                <span>«</span>
            @else
                <a href="{{ $processes->previousPageUrl() }}">«</a>
            @endif

            @foreach ($processes->getUrlRange(1, $processes->lastPage()) as $page => $url)
                @if ($page === $processes->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($processes->hasMorePages())
                <a href="{{ $processes->nextPageUrl() }}">»</a>
            @else
                <span>»</span>
            @endif
        </div>
    @endif
</body>
</html>
