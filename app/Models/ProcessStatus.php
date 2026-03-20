<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessStatus extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'ps_id';
    protected $table = 'process_status';
    protected $fillable = ['ps_name'];
}
