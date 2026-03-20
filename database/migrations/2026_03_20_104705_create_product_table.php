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
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('product_name', 255);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('manufacturer_id');

            $table->foreign('manufacturer_id')
                ->references('manufacturer_id')
                ->on('manufacturer')
                ->onDelete('restrict');

            $table->index('category_id');
            $table->index('manufacturer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
