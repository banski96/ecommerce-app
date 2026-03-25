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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id('shipments_id');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');
            $table->dateTime('shipping_date')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
