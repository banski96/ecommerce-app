<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');

            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('cart_id')
                ->references('cart_id')
                ->on('carts')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade');

            $table->integer('quantity')->unsigned();

            // Prevent duplicate product in cart
            $table->unique(['cart_id', 'product_id']);

            // Indexes
            $table->index('cart_id');
            $table->index('product_id');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};