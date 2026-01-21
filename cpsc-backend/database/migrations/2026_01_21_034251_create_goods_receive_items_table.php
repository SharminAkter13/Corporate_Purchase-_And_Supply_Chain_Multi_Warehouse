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
        Schema::create('goods_receive_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receive_id')->constrained()->cascadeOnDelete();
            $table->foreignId('purchase_order_item_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->decimal('qty_received', 12, 2);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receive_items');
    }
};
