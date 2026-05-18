<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Enable the fuzz search extension in Postgres
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm;');
    }

    public function down(): void
    {
        DB::statement('DROP EXTENSION IF EXISTS pg_trgm;');
    }
};
