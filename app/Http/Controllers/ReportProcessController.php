<?php

namespace App\Http\Controllers;

use App\Models\ReportProcess;
use App\Repositories\ReportProcessRepository;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportProcessController extends Controller
{
    public function __construct(
        private readonly ReportProcessRepository $reportProcessRepository,
    ) {
    }

    public function index()
    {
        $processes = $this->reportProcessRepository->paginate();

        return view('reports.index', compact('processes'));
    }

    public function download(ReportProcess $reportProcess): StreamedResponse
    {
        $path = $reportProcess->rp_file_save_path;

        abort_unless($path && Storage::exists($path), 404);

        return Storage::download($path, basename($path));
    }
}
