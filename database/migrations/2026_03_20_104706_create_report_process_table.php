<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_process', function (Blueprint $table) {
            $table->bigIncrements('rp_id');
            $table->integer('rp_pid');
            $table->timestamp('rp_start_datetime');
            $table->unsignedInteger('rp_exec_time')->nullable()->comment('время выполнения в миллисекундах');
            $table->unsignedBigInteger('ps_id');
            $table->string('rp_file_save_path', 500)->nullable();

            $table->foreign('ps_id')
                ->references('ps_id')
                ->on('process_status')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_process');
    }
};
