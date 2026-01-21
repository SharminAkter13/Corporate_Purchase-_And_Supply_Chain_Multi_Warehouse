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
        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->string('ref_type'); 
            $table->unsignedBigInteger('ref_id');
            $table->decimal('qty_in', 12, 2)->nullable();
            $table->decimal('qty_out', 12, 2)->nullable();
            $table->decimal('balance_after', 12, 2);
            $table->timestamps();

            $table->index(['ref_type', 'ref_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ledgers');
    }
};
