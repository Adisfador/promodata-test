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
        Schema::create('price', function (Blueprint $table) {
            $table->bigIncrements('price_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('price', 10, 2);
            $table->date('price_date');

            $table->foreign('product_id')
                ->references('product_id')
                ->on('product')
                ->onDelete('cascade');

            $table->index(['product_id', 'price_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price');
    }
};
