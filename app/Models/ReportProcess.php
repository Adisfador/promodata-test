<?php

namespace App\Models;

use App\Enums\ProcessStatus as ProcessStatusEnum;
use Illuminate\Database\Eloquent\Model;

class ReportProcess extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'rp_id';
    protected $table = 'report_process';
    protected $fillable = ['rp_pid', 'rp_start_datetime', 'rp_exec_time', 'ps_id', 'rp_file_save_path'];

    protected $casts = [
        'rp_start_datetime' => 'datetime',
        'ps_id'            => ProcessStatusEnum::class,
    ];

    public function status()
    {
        return $this->belongsTo(ProcessStatus::class, 'ps_id', 'ps_id');
    }
}
