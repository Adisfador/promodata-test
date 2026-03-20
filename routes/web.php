<?php

use App\Http\Controllers\ReportProcessController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ReportProcessController::class, 'index'])->name('reports.index');
Route::get('/reports/{reportProcess}/download', [ReportProcessController::class, 'download'])->name('reports.download');
