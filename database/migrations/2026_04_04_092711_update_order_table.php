<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ========================
        // UPDATE ORDERS TABLE
        // ========================
        Schema::table('orders', function (Blueprint $table) {
            // Make reference number unique
            $table->string('reference_number')->unique()->change();

            // Add index for performance
            $table->index('user_id');
        });

        // Change status column to enum via raw SQL (PostgreSQL safe)
        DB::statement("
            ALTER TABLE orders
            ALTER COLUMN status DROP DEFAULT,
            ALTER COLUMN status TYPE varchar(255) USING status::varchar;
        ");

        // Add check constraint for enum values
        DB::statement("
            ALTER TABLE orders
            ADD CONSTRAINT orders_status_check
            CHECK (status IN ('pending', 'processing', 'shipped', 'completed', 'cancelled'))
        ");

        DB::statement("
            ALTER TABLE orders
            ALTER COLUMN status SET DEFAULT 'pending';
        ");

        // ========================
        // UPDATE ORDER_ITEMS TABLE
        // ========================
        Schema::table('order_items', function (Blueprint $table) {
            // Make quantity unsigned (PostgreSQL only supports integer >=0)
            $table->integer('quantity')->unsigned()->change();

            // Prevent duplicate products in same order
            $table->unique(['order_id', 'product_id']);

            // Add indexes
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        // ========================
        // REVERT ORDERS TABLE
        // ========================
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['reference_number']);
            $table->dropIndex(['user_id']);
        });

        // Drop check constraint
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");

        // Revert status column back to string without enum
        DB::statement("
            ALTER TABLE orders
            ALTER COLUMN status TYPE varchar(255),
            ALTER COLUMN status SET DEFAULT 'pending';
        ");

        // ========================
        // REVERT ORDER_ITEMS TABLE
        // ========================
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropUnique(['order_id', 'product_id']);
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
            $table->integer('quantity')->change();
        });
    }
};